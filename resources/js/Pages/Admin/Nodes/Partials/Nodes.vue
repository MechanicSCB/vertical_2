<script setup>
import Node from "./Node.vue";
import {router} from "@inertiajs/vue3";
import {ref, inject} from "vue";

let moveMode = inject('moveMode');
let openedNodes = inject('openedNodes');

let props = defineProps({parent: Object});

function getSortedNodes(){
    let arr = [];

    for (let key of Object.keys(props.parent.nodes ?? [])){
        arr.push(props.parent.nodes[key])
    }

    return arr.sort((a,b) => a['order'] - b['order'])
}

function dragStart(event, draggedNodeId) {
    event.dataTransfer.setData("draggedNodeId", draggedNodeId); //event.dataTransfer.effectAllowed = 'move'; event.dataTransfer.setDragImage(ev.target,100,100); return true;
}

function dragEnter(event, el) {/* ev.preventDefault(); return true;*/
}

function dragOver(event, el) {/* ev.preventDefault();*/
}

function dragDrop(event, destNodeId) {
    var draggedNodeId = event.dataTransfer.getData("draggedNodeId");

    if (moveMode.value === 'move') {
        router.post(route('nodes.move', [draggedNodeId, destNodeId]))
    } else if (moveMode.value === 'copy') {
        router.post(route('nodes.copy', [draggedNodeId, destNodeId]))
    } else if (moveMode.value === 'reorder') {
        router.post(route('nodes.reorder', [draggedNodeId, destNodeId]))
    }
}

function toggle(node) {
    if (openedNodes.value.includes(node.id)) {
        openedNodes.value = openedNodes.value.filter(item => item !== node.id)
    } else {
        openedNodes.value.push(node.id);
    }

    sessionStorage.setItem('openedNodes', JSON.stringify(openedNodes.value))
}

</script>
<template>
    <div class="flex flex-col w-full">
        <div v-for="node in getSortedNodes()" class="">
            <div class="flex gap-1">
                <div @click="toggle(node)" class="cursor-pointer" draggable="true"
                     @dragenter.prevent="dragEnter($event, $el)"
                     @dragover.prevent="dragOver($event, $el)"
                     @drop.prevent="dragDrop($event,node.id)"

                     @dragstart="dragStart($event,node.id)"
                >
                    <Node :node="node"></Node>
                </div>
                <button
                    class="ml-1 text-xxs text-ui-text-accent"
                    @click="router.delete(route('nodes.destroy', node.id))"
                >
                    del
                </button>
            </div>
            <div v-if="openedNodes.includes(node.id)" class="flex">
                <Nodes class="ml-6" :parent="node"></Nodes>
            </div>
        </div>
    </div>
</template>

