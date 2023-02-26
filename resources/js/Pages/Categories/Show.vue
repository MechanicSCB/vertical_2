<script setup>
import CategoriesGrid from "./Partials/CategoriesGrid.vue";
import ProductsGrid from "./Partials/ProductsGrid.vue";
import Breadcrumbs from "../../Layouts/Partials/Breadcrumbs.vue";
import {router} from "@inertiajs/vue3";

let props = defineProps({
    categoryNode:Object,
    subCategories:Object,
    breadcrumbs:Object,
});
</script>
<template>
    <Breadcrumbs :breadcrumbs="breadcrumbs"/>
    <div class="">
        <h1 class="mt-8 ml-10 lg:text-5xl md:text-4xl md:text-left text-center text-3xl font-semibold md:mb-20 mb-12">
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
    </div>
</template>
