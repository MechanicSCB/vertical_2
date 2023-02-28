<script setup>
let props = defineProps({
    breadcrumbs:Array
});
</script>
<template>
    <div class="lg:mt-10 mt-8">
        <div class="pl-10 md:flex hidden lg:justify-center">
            <Link href="/" class="text-ui-text-accent">Главная</Link>
            <div v-for="(crumb,key) in breadcrumbs" class="flex items-center">
                <hr class="mt-1 mx-4 w-5 border-ui-text-secondary">

                <Link v-if="Object.keys(breadcrumbs).length - key - 100" :href="crumb.url" class="text-ui-text-accent">{{ crumb.title }}</Link>
                <div v-else class="text-ui-text-secondary">{{ crumb.title }}</div>
            </div>
        </div>
    </div>
</template>
