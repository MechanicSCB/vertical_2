<script setup>
import ProductCard from "./ProductCard.vue";

let props = defineProps({
    products:Object,
});
</script>

<template>
    <div class="mx-auto md:px-10 px-2 grid 2xl:grid-cols-6 xl:grid-cols-5 lg:grid-cols-4 md:grid-cols-3 grid-cols-2 max-w-[1656px] gap-x-4 gap-y-8">
        <div v-for="product in products">
            <ProductCard :product="product"/>
        </div>
    </div>
</template>
