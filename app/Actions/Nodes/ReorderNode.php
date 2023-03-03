<?php


namespace App\Actions\Nodes;


use App\Models\Node;

class ReorderNode
{
    public function reorder(Node $targetNode, int $pos)
    {
        // Берем всех соседей целевого узла вместе с самим целевым узлом
        $siblings = Node::query()
            ->where('parent_path', $targetNode['parent_path'])
            ->orderBy('order')
            ->toBase()
            ->get(['id', 'order','path','category_id'])
            ->keyBy('id');

        $siblings = stdToArray($siblings);

        // пересчитываем ордеры для всех соседних узлов
        $order = 1;

        foreach ($siblings as $nodeId => $sibling) {
            $siblings[$nodeId]['order'] = $order++;
        }

        $targetOrder = $siblings[$targetNode['id']]['order'];

        foreach ($siblings as $nodeId => $sibling) {
            if ($pos <= $sibling['order'] && $sibling['order'] < $targetOrder) {
                $siblings[$nodeId]['order'] = $sibling['order'] + 1;
            }
            if ($targetOrder < $sibling['order'] && $sibling['order'] <= $pos) {
                $siblings[$nodeId]['order'] = $sibling['order'] - 1;
            }
        }

        $siblings[$targetNode['id']]['order'] = $pos;

        Node::query()->upsert($siblings, 'id', ['id', 'order']);

        return ['type' => 'success', 'body' => __('flash.successfully_reordered')];
    }
}
