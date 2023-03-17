<?php


namespace App\Classes;


use App\Models\Node;
use Illuminate\Support\Facades\DB;

class NodesHandler
{
    public function getNodesCategoriesIds(array $nodesIds):array
    {
        $categoriesIds = [];

        foreach ($nodesIds as $nodeId){
            $categoriesIds =[ ...$categoriesIds,...$this->getNodeCategoriesIds($nodeId)];
        }

        $categoriesIds = array_unique($categoriesIds);

        return $categoriesIds;
    }

    public function getNodeCategoriesIds(int $nodeId):array
    {
        $nodePath = Node::query()->find($nodeId)['path'];
        $categoriesIds = DB::table('nodes')
            ->where('path','like',"$nodePath%")
            ->pluck('category_id')
            ->toArray()
        ;

        return $categoriesIds;
    }
}
