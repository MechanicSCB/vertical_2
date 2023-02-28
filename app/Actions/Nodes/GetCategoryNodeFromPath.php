<?php


namespace App\Actions\Nodes;


use App\Models\Node;

class GetCategoryNodeFromPath
{
    public function get(string $path, array $ancestorsCategories = null): Node
    {
        $ancestorsCategories ??= (new GetAncestorsCategoriesFromPath())->get($path);
        $nodePath = '';

        foreach (explode('/', $path) as $slug) {
            $nodePath .= $ancestorsCategories[$slug]['id'] . Node::$separator;
        }

        $categoryNode = Node::query()
            ->with('category:id,slug,title') // in order not to take all category fields
            ->where('path', '=', trim($nodePath, Node::$separator))
            ->firstOrFail()
            ->append('title')
        ;

        return $categoryNode;
    }
}
