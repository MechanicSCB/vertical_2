<script setup>
import {ref, inject, reactive} from "vue";
import MagnifyIcon from "../../Svg/MagnifyIcon.vue";
import CloseIcon from "../../Svg/CloseIcon.vue";

let showSearchArea = inject('showSearchArea');
let searchResults = reactive({});
let searchString = ref('');

async function getSearchResults() {
    searchResults.value = (await axios.post('/get-search-results', {searchString: searchString.value})).data;
}

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
</script>
<template>
    <div id="search_area">
        <!-- Transparent Back -->
        <div @click="showSearchArea=false" class="fixed top-0 left-0 z-[75] w-full h-screen bg-[rgba(0,0,0,0.3)]"></div>

        <!-- Search Area -->
        <div @click.stop class="absolute z-[75] top-8 left-0 right-0 mx-auto max-w-[1200px] bg-ui-body rounded">
            <div class="flex">
                <div class="relative my-8 mx-24 w-full h-12 rounded flex">
                    <div class="text-lg font-bold w-32 h-full bg-ui-accent_light justify-center flex items-center shrink-0">
                        <button @click="showCategoryFilter = !showCategoryFilter">Везде</button>
                    </div>
                    <div class="w-[1px] bg-ui-text-light my-2"></div>
                    <div class="w-full">
                        <div class="flex h-full items-center">
                            <input @input="getSearchResults()" v-model="searchString"
                                   class="px-6 border-0 w-full bg-ui-accent_light  h-full text-lg focus:ring-0"
                                   placeholder="Что вы хотите найти?" type="text">
                            <button @click="searchString = '';searchResults = {}" class="-ml-[70px] bg-ui-text-secondary rounded-full w-5 h-5 fill-ui-text-accent_inverse">
                                <CloseIcon/>
                            </button>
                        </div>

                        <!-- SEARCH RESULTS-->
                        <div v-if="Object.keys(searchResults).length"
                             class="bg-ui-body px-6 overflow-y-auto rounded-b pb-5 mb-10">

                            <div class="text-sm">
                                <!-- Categories -->
                                <div class="my-3">Всего категорий {{ searchResults.value.categories.total }}</div>
                                <div v-if="searchResults.value.categories?.total" class="max-h-48 overflow-y-auto">
                                    <div v-for="category in searchResults.value.categories?.data ?? []">
                                        <div class="font-semibold">{{ category.title }} ({{ category.slug }})</div>
                                    </div>
                                </div>

                                <!-- Products -->
                                <div class="my-2">Товаров найдено {{ searchResults.value.products?.total ?? '--' }}</div>
                                <div class="max-h-48 overflow-y-auto">
                                    <div v-for="product in searchResults.value.products?.data ?? []">
                                        <div class="font-semibold cursor-pointer">{{ product.name }}</div>
                                    </div>
                                </div>

                                <!-- Facets -->
                                <div class="my-3">По категориям, в {{ facetLen }}</div>
                                <div v-if="facetLen = Object.keys(facet = searchResults.value.facet).length" class="max-h-64 overflow-y-auto">
                                    <div v-for="category in sortedByChildren(facet)">
                                        <div class="font-semibold">{{ category.title }} ({{ category.itemsCount }})</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-lg font-bold w-28 bg-ui-accent h-full shrink-0 rounded-r flex justify-center items-center cursor-pointer">
                        <MagnifyIcon class="w-6 fill-ui-text-accent_inverse"/>
                    </div>
                </div>
                <div class="absolute right-10 h-full flex items-center">
                    <button @click="showSearchArea=false" class="rounded-full w-5 h-5 fill-ui-text-secondary">
                        <CloseIcon/>
                    </button>
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
