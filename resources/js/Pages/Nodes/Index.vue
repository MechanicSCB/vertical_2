<script setup>
import Nodes from "./Partials/Nodes.vue";
import {provide, ref} from "vue";

let props = defineProps({
    tree:Object,
});

let moveMode = ref('move');
let openedNodes = ref( JSON.parse(sessionStorage.getItem('openedNodes')) ?? [1]);

provide('moveMode', moveMode)
provide('openedNodes', openedNodes)


</script>
<template>
    <div class="px-12">
        <h1 class="mt-8 ml-10 lg:text-5xl md:text-4xl md:text-left text-center text-3xl font-semibold md:mb-20 mb-12">
            Каталог товаров
        </h1>
        <div>
            <select v-model="moveMode" class="rounded-3xl mb-4">
                <option>move</option>
                <option>copy</option>
                <option>reorder</option>
            </select>
        </div>

        <Nodes :parent="tree"/>
    </div>
</template>
