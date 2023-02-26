<script setup>
import ProductCard from "./ProductCard.vue";
import Pagination from "./Pagination.vue";
let props = defineProps({
    products:Object,
});
</script>

<template>
    <div>
        <Pagination :links="products.links" class="mt-3 mb-4"/>

        <div class="grid xl:grid-cols-2 grid-cols-1 gap-9">
            <div v-for="product in products.data">
                <ProductCard :product="product"/>
            </div>
        </div>

        <Pagination :links="products.links" class="mt-3 mb-4"/>
    </div>
</template>
