<script setup>
import SearchResultsProducts from "@/Layouts/SearchArea/Partials/SearchResultsProducts.vue";
import {inject} from "vue";

let props = defineProps({results:Object});

let searchString = inject('searchString');
function sortedByChildren(children) {
    let sortable = [];

    for (let item of Object.values(children)) {
        sortable.push(item);
    }

    sortable.sort(function (a, b) {
        return Object.keys(b.children ?? []).length - Object.keys(a.children ?? []).length;
    });

    return sortable;
}

function replaceWordToHintAndSubmit(word,hint) {
    searchString.value = searchString.value.replace(word,hint);
}
</script>
<template>
    <div v-if="Object.keys(results).length" class="bg-ui-body pb-5 mb-10">
        <div class="text-sm">
            <!-- SEARCH HINTS -->
            <div>
                <div v-for="(wordHints, word) in results?.hints ?? []">
                    <button v-for="hint in wordHints"
                            @click="replaceWordToHintAndSubmit(word, hint)" class="text-sm mr-2"
                    >{{ hint }}
                    </button>
                </div>
            </div>

            <!-- Categories -->
            <div class="my-3">Всего категорий {{ results?.categories?.total }}</div>
            <div v-if="results.categories?.total" class="max-h-48 overflow-y-auto">
                <div v-for="category in results.categories?.items ?? []">
                    <Link :href="category.nodes[0].url" class="font-semibold">{{ category.title }}</Link>
                </div>
            </div>

            <!-- Products -->
            <SearchResultsProducts :products="results.products"/>

            <!-- Facets -->
            <div class="my-3">По категориям, в {{ facetLen = Object.keys(facet = results.facet ?? {}).length }}</div>
            <div v-if="facetLen" class="max-h-64 overflow-y-auto">
                <div v-for="category in sortedByChildren(facet)">
                    <Link v-for="node in category?.nodes" :href="node.url" class="font-semibold">{{ category.title }} ({{ category.itemsCount }})</Link>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
#search_area div{
    scrollbar-color:  var(--color-ui-secondary) var(--color-ui-light);
}
</style>
