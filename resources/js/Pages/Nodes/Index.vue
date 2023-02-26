<script setup>
import Nodes from "./Partials/Nodes.vue";
import {provide, ref} from "vue";

let props = defineProps({
    tree:Object,
});

let copyMode = ref(false);
let openedNodes = ref( JSON.parse(sessionStorage.getItem('openedNodes')) ?? [1]);

provide('copyMode', copyMode)
provide('openedNodes', openedNodes)


</script>
<template>
    <div class="px-12">
        <h1 class="mt-8 ml-10 lg:text-5xl md:text-4xl md:text-left text-center text-3xl font-semibold md:mb-20 mb-12">
            Каталог товаров
        </h1>
        <div>
            <button class="px-2 pb-1 text-white rounded"
                    :class="copyMode ?'bg-ui-accent':'bg-ui-secondary'"
                    @click="copyMode = !copyMode">
                {{copyMode ? 'copy' : 'move'}}
            </button>
        </div>

        <Nodes :parent="tree"/>
    </div>
</template>
