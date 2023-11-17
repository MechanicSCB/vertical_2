<?php


namespace App\Classes;


use App\Models\Node;

class TreeHandler
{
    /**
     * Reference tree making function, it works much faster (3-5 times on big trees), than getTreeRec with addNodeToTree recursive function
     * Require sorting nodes by level
     * if you don't use reference and set nodes to tree directly, it can be a little faster on big deep trees
     */
    public function getTree(array $nodes): array
    {
        $tree = [];

        foreach ($nodes as $node) {
            $keys = explode(Node::$separator, $node['parent_path']);

            $tmp = &$tree;

            for ($i = 0; $i < $node['level']; $i++) {
                $tmp = &$tmp[$keys[$i]]['nodes'];
            }

            $tmp[$node['category_id']] = $node;
        }

        return $tree;
    }

    /**
     * Recursive tree making function, use data_set function analog for each node
     * Does not require sorting nodes by level
     */
    public function getTreeRec(array $nodes): array
    {
        $tree = [];

        foreach ($nodes as $node) {
            $parentIds = explode(Node::$separator, $node['parent_path']);

            if ($node['level'] === 0) {
                $key = $node['category_id'];
            } else {
                $key = implode('.nodes.', $parentIds) . '.nodes.' . $node['category_id'];
            }

            $keys = explode('.', $key);

            $this->addNodeToTree($tree, $keys, $node);
        }

        return $tree;
    }

    /**
     * Laravel data_set analog, but it works about twice faster in this case due to fewer checks
     * and also does not require sorting nodes by level
     */
    public function addNodeToTree(&$tree, $keys, $node): array
    {
        $key = array_shift($keys);

        if (! $keys) {
            return $tree[$key] = [...$tree[$key] ?? [], ...$node];
        }

        return $this->addNodeToTree($tree[$key], $keys, $node);
    }

}
