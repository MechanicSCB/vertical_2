<script setup>
let props = defineProps({
    category:Object,
});
</script>

<template>
    <Link :href="category.url" class="border h-full hover:border-ui-border-hover rounded-3xl bg-ui-section overflow-hidden pb-6 flex flex-col cursor-pointer">
        <div class="mx-auto p-2 flex items-center mb-4 lg:h-[219px] md:h-36 h-28">
             <img class="max-h-full" :src="category.image" :alt="category.slug">
        </div>
        <h3 class="mx-4 text-[14px] font-bold h-fit">{{ category.title }}</h3>
    </Link>
</template>
<style scoped>
div:hover > h3{
    color: var(--color-ui-link-text-hover);
}
</style>
