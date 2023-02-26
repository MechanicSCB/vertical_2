<?php

namespace App\Http\Controllers;

use App\Actions\Nodes\MoveOrCopyNode;
use App\Classes\TreeHandler;
use App\Models\Node;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Response;
use Inertia\ResponseFactory;

class NodeController extends Controller
{
    public function index(): Response|ResponseFactory
    {
        $nodes = DB::table('nodes')
            ->join('categories','category_id','=','categories.id')
            ->orderBy('level') // if use getTree or data_set in getTreeRec nodes must be sorted by level
            ->orderBy('order')
            ->get(['nodes.id','parent_path', 'category_id', 'categories.slug', 'categories.title', 'path', 'level', 'order']);

        $tree['nodes'] = (new TreeHandler())->getTree(stdToArray($nodes));
        //$tree['nodes'] = (new TreeHandler())->getTreeRec(stdToArray($nodes));

        return inertia('Nodes/Index', compact('tree'));
    }

    public function copy(Node $targetNode, Node $destNode): RedirectResponse
    {
        // $actionResultMsg = ['type'=>'success','body' => 'успешно скопировано']
        $actionResultMsg = (new MoveOrCopyNode())->moveOrCopyNode($targetNode, $destNode, true);

        return back()->with($actionResultMsg['type'], $actionResultMsg['body']);
    }

    public function move(Node $targetNode, Node $destNode): RedirectResponse
    {
        $actionResultMsg = (new MoveOrCopyNode())->moveOrCopyNode($targetNode, $destNode, false);

        return back()->with($actionResultMsg['type'], $actionResultMsg['body']);
    }

    public function destroy(Node $node): RedirectResponse
    {
        $node->delete();

        return back()->with('success', __('flash.successfully_deleted'));
    }
}
