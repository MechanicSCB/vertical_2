<script setup>
import Breadcrumbs from "../../Layouts/Partials/Breadcrumbs.vue";
import {router} from "@inertiajs/vue3";
import CategoriesGrid from "./Partials/CategoriesGrid.vue";
import ProductsFilter from "./Partials/ProductsFilter.vue";
import ProductsGrid from "./Partials/ProductsGrid.vue";

let props = defineProps({
    categoryNode:Object,
    subCategories:Object,
    breadcrumbs:Object,
    filterData:Object,
    products:Object,
    time:String,
});
</script>
<template>
    <Head :title="categoryNode.title +' - купить в интернет-магазине Вертикаль'"/>
    <Breadcrumbs :breadcrumbs="breadcrumbs"/>
    <div class="mx-auto px-9 max-w-[1656px]">
        <h1 class="mt-8 lg:text-5xl md:text-4xl md:text-left text-center text-3xl font-semibold md:mb-20 mb-12">
            {{ categoryNode.title }}
            <!-- TODO TEMP -->
            <span class="text-xs text-ui-text-secondary">({{ time }})</span>
        </h1>

        <!--<CategoriesGrid :categories="subCategories"/>-->

        <div class="mt-10 flex md:flex-row flex-col gap-8">
            <ProductsFilter class="2xl:w-1/4 xl:w-1/3 md:w-1/2 w-full" :productsTotal="products.total" :filterData="filterData" :time="time"/>
            <ProductsGrid class="2xl:w-3/4 xl:w-2/3 md:w-1/2 w-full" :products="products"/>
        </div>
    </div>
</template>
