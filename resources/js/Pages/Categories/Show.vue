<script setup>
import CategoriesGrid from "./Partials/CategoriesGrid.vue";
import ProductsGrid from "./Partials/ProductsGrid.vue";
import Breadcrumbs from "../../Layouts/Partials/Breadcrumbs.vue";
import {router} from "@inertiajs/vue3";
import ProductsFilter from "./Partials/ProductsFilter.vue";

let props = defineProps({
    categoryNode:Object,
    subCategories:Object,
    breadcrumbs:Object,
    filterData:Object,
    products:Object,
});
</script>
<template>
    <Breadcrumbs :breadcrumbs="breadcrumbs"/>
    <div class="mx-auto px-9 max-w-[1656px]">
        <h1 class="mt-8 lg:text-5xl md:text-4xl md:text-left text-center text-3xl font-semibold md:mb-20 mb-12">
            {{ categoryNode.title }}
            <button class="text-xs px-1 rounded bg-gray-200"><Link :href="route('categories.edit', categoryNode.category.id)">edit</Link></button>
            <button
                class="ml-1 text-xs px-1 rounded bg-ui-accent"
                @click="router.delete(route('categories.destroy', categoryNode.category.id))"
            >
                del
            </button>
        </h1>

        <CategoriesGrid :categories="subCategories"/>

        <div class="mt-10 flex md:flex-row flex-col gap-8">
            <ProductsFilter class="lg:w-1/3 md:w-1/2 w-full" :products="products" :filterData="filterData"/>
            <ProductsGrid class="lg:w-2/3 md:w-1/2 w-full" :products="products"/>
        </div>
    </div>
</template>
