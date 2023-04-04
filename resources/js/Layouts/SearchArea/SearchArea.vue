<script setup>
import {ref, inject, reactive, watch, provide, onMounted} from "vue";
import MagnifyIcon from "../../Svg/MagnifyIcon.vue";
import CloseIcon from "../../Svg/CloseIcon.vue";
import SearchResults from "@/Layouts/SearchArea/Partials/SearchResults.vue";
import {throttle} from "lodash";

let showSearchArea = inject('showSearchArea');
let searchResults = reactive({});
let searchString = ref('');
let showedResults = ref('meili');
const searchInputEl = ref(null)

provide('searchString',searchString);

onMounted(() => {
    // TODO focus does not work
    searchInputEl.value.focus();
    watch(showSearchArea, searchInputEl.value.focus());
});

async function getSearchResults() {
    searchResults.value = (await axios.post('/get-search-results', {searchString: searchString.value})).data;
}

// TODO import lodash throw error when ssr server started
watch(searchString, throttle(() =>getSearchResults() , 500));
// watch(searchString, getSearchResults());
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
                            <input ref="searchInputEl" v-model="searchString" placeholder="Что вы хотите найти?" type="text"
                                   class="px-6 border-0 w-full bg-ui-accent_light  h-full text-lg focus:ring-0">
                            <button v-if="searchString" @click="searchString = '';searchResults = {}" class="-ml-[70px] bg-gray-300 rounded-full w-5 h-5 fill-ui-text-accent_inverse">
                                <CloseIcon/>
                            </button>
                        </div>

                        <!-- SEARCH RESULTS-->
                        <div v-if="Object.keys(searchResults).length" class="bg-ui-body rounded-b">
                            <!-- SEARCH RESULTS TAB LABELS -->
                            <div class="mt-2 px-6 flex gap-4">
                                <div @click="showedResults='meili'"
                                     class="px-3 cursor-pointer rounded-t border border-b-0"
                                     :class="showedResults==='meili' ? 'text-ui-text-accent':'text-ui-text-secondary border-[rgba(0,0,0,0)]'"
                                >meili</div>
                                <div @click="showedResults='pgsql'"
                                     class="px-3 cursor-pointer rounded-t border border-b-0"
                                     :class="showedResults==='pgsql' ? 'text-ui-text-accent':'text-ui-text-secondary border-[rgba(0,0,0,0)]'"
                                >pgsql</div>
                            </div>

                            <!-- SEARCH RESULTS TABS CONTENT -->
                            <div class="px-6">
                                <SearchResults v-show="showedResults === 'meili'"
                                               :results="searchResults?.value?.meili ?? {}"/>
                                <SearchResults v-show="showedResults === 'pgsql'"
                                               :results="searchResults?.value?.pgsql ?? {}"/>
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
