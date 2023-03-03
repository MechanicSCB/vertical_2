<?php


namespace App\Actions\Nodes;


use App\Models\Node;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;

class MoveOrCopyNode
{
    #[ArrayShape(['type' => "string", 'body' => "string"])]
    public function moveOrCopyNode(Node $targetNode, Node $destNode, bool $isCopyMode = false): array
    {
        $sep = Node::$separator;
        // check that the destination node is already parent of target node ($node['parent_path'] === $parentNode['id'])
        // if you remove this check, the nodes will simply be updated unchanged (only updated_at will change) for copy and for move actions both
        if ($targetNode['parent_path'] === $destNode['path']) {
            return ['type' => 'warning', 'body' => __('flash.the_destination_node_is_already_parent_of_target_node')];
        }

        // forbid moving the node into itself
        if ($targetNode['path'] === $destNode['path']
            // копирование в самого себя или своих потомков работает, но поведение может привести к неожиданному результату,
            // если узел назначения уже содержит узел, ссылающийся на ту же категорию, что и он сам,
            // то к потомкам такого узла добаляются потомки копируемого узла
            // comment line below to forbid copying a node to itself
            && $isCopyMode === false
        ) {
            return ['type' => 'error', 'body' => __('flash.moving_a_node_into_itself_is_prohibited')];
        }

        // forbid moving the node into its descendants
        if (str_starts_with($destNode['path'], $targetNode['path'] . $sep)
            // comment line below to prohibit copying a node to its descendants
            && $isCopyMode === false
        ) {
            return ['type' => 'error', 'body' => __('flash.moving_a_node_into_its_descendants_is_prohibited')];
        }

        // check that the destination copy is not already exist in a destination node (two clones in one parent)
        if(Node::query()->where('parent_path','=', $destNode['path'])->pluck('category_id')->contains($targetNode['category_id'])){
            return ['type' => 'error', 'body' => __('flash.this_category_node_already_exist_in_destination_node')];
        }

        $keys = $isCopyMode ? ['parent_path', 'path', 'category_id', 'order'] : ['id', 'parent_path', 'path', 'category_id'];

        // Get target descendants if they exist as array of arrays with selected fields
        $targetDescendants = stdToArray(DB::table('nodes')
            ->where('path', 'like', "{$targetNode['path']}$sep%")
            ->get($keys));

        // Update target descendants paths and parent paths
        $oldParentPath = $sep . $targetNode['path'] . $sep;
        $newParentPath = $sep . $destNode['path'] . $sep . $targetNode['category_id'] . $sep;

        foreach ($targetDescendants as &$descendant) {
            $descendant['path'] = trim(str_replace($oldParentPath, $newParentPath, $sep . $descendant['path'] . $sep), $sep);
            $descendant['parent_path'] = trim(str_replace($oldParentPath, $newParentPath, $sep . $descendant['parent_path'] . $sep), $sep);
        }

        // Get target node as array with selected fields
        $targetNode = stdToArray(DB::table('nodes')->where('path', '=', $targetNode['path'])->first($keys));

        // Update the path to the target node and the parent path
        $targetNode['parent_path'] = $destNode['path'];
        $targetNode['path'] = $destNode['path'] . $sep . $targetNode['category_id'];

        // Merge the array of the target node and the arrays of its descendants
        $nodesToUpdate = [$targetNode, ...$targetDescendants];

        // Update nodes DB table
        if ($isCopyMode) {
            Node::query()->upsert($nodesToUpdate, ['path'], ['parent_path', 'path']);

            return ['type' => 'success', 'body' => __('flash.successfully_copied')];
        }

        Node::query()->upsert($nodesToUpdate, 'id', ['id', 'path', 'parent_path']);

        return ['type' => 'success', 'body' => __('flash.successfully_moved')];
    }
}
