<script setup>
import CategoriesGrid from "./Partials/CategoriesGrid.vue";

let props = defineProps({
    mainCategories:Object,
});

</script>
<template>
    <div class="">
        <h1 class="mt-8 ml-10 lg:text-5xl md:text-4xl md:text-left text-center text-3xl font-semibold md:mb-20 mb-12">
            Каталог товаров
        </h1>

        <CategoriesGrid :categories="mainCategories"/>
    </div>
</template>
