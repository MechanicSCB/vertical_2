<script setup>
import {ref, inject, reactive, onMounted} from "vue";

let tree = reactive({});
let showedChildrenNode = ref(null);

onMounted(() => {
    getTree();
});

let filterForm = inject('filterForm');
let showNodesFilter = inject('showNodesFilter');

async function getTree() {
    tree = reactive((await axios.get(route('nodes.tree'))).data);
}

function toggleNode(nodeId) {
    if (filterForm['nodes'].includes(nodeId)) {
        filterForm['nodes'] = filterForm['nodes'].filter(item => item !== nodeId)
    } else {
        filterForm['nodes'].push(nodeId);
    }

    filterForm.get('',{preserveScroll: true, preserveState: true});
    showNodesFilter.value = false;
}

function sortedByChildren(nodes) {
    let sortable = [];

    for (let item of Object.values(nodes)) {
        sortable.push(item);
    }

    sortable.sort(function (a, b) {
        return Object.keys(b.nodes ?? []).length - Object.keys(a.nodes ?? []).length;
    });

    return sortable;
}
</script>
<template>
    <div class="absolute border rounded left-0 top-40 px-4 pt-5 w-full min-h-screen bg-ui-body text-sm">
        <div @click="filterForm['nodes'] = []" class="mt-1 pl-3 cursor-pointer">
            Везде
        </div>

        <div v-for="mainNode in tree[1]?.nodes ?? []" class="flex pl-3">
            <div class="my-1 group cursor-pointer w-[150px]">
                <div @click="toggleNode(mainNode.id)" @mouseenter.="showedChildrenNode = mainNode.id"
                     :class="{'text-ui-text-accent':showedChildrenNode === mainNode.id}"
                >{{ mainNode.title }}</div>

                <div v-if="showedChildrenNode === mainNode.id"
                     class="pt-5 absolute min-h-screen pb-20 left-[200px] top-0 flex flex-wrap gap-y-4 max-h-screen overflow-y-auto"
                >
                    <div v-for="node in sortedByChildren(mainNode.nodes)" class="w-1/3 px-3">
                        <div @click="toggleNode(node.id)" class="font-semibold text-sm hover:text-ui-link-hover">
                            {{ node.title }}
                        </div>
                        <div class="text-xs">
                            <div @click="toggleNode(subnode.id)" v-for="subnode in node.nodes" class="my-1 hover:text-ui-link-hover">
                                {{ subnode.title }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
#search_area div {
    scrollbar-color: var(--color-ui-secondary) var(--color-ui-light);
}
</style>
