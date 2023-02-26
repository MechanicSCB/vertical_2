<script setup>
let props = defineProps({
    product:Object,
});
</script>

<template>
    <div class="border hover:border-ui-border-hover rounded-3xl bg-ui-section overflow-hidden pb-6 flex flex-col cursor-pointer">
        <Link :href="'/products/'+product.id" class="mx-auto mb-4 lg:h-full md:h-36 h-28">
            <img class="h-full" :src="'/storage/images/products/'+product.code+'.jpg'">
        </Link>
        <h3 class="ml-4 text-[14px] font-bold h-8">{{ product.name }}</h3>
    </div>
</template>
<style scoped>
div:hover > h3{
    color: var(--color-ui-link-text-hover);
}
</style>
