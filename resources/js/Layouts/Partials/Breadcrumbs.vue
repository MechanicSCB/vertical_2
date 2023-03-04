<script setup>
let props = defineProps({
    breadcrumbs:Array
});
</script>
<template>
    <div class="lg:mt-10 mt-8">
        <div class="pl-10 hidden md:flex flex-wrap lg:justify-center gap-y-4">
            <div class="flex items-center">
                <Link href="/" class="text-ui-text-accent border-b border-[rgba(0,0,0,0)] hover:border-ui-text-accent">Главная</Link>
                <hr class="mt-1 mx-4 w-5 border-ui-text-secondary">
            </div>

            <div v-for="(crumb,key) in breadcrumbs">
                <Link v-if="Object.keys(breadcrumbs).length - key > 1" :href="crumb.url" class="text-ui-text-accent flex items-center">
                    <div class=" border-b border-[rgba(0,0,0,0)] hover:border-ui-text-accent">
                        {{ crumb.title }}
                    </div>
                    <hr class="mt-1 mx-4 w-5 border-ui-text-secondary">
                </Link>
                <div v-else class="text-ui-text-secondary">
                    {{ crumb.title }}
                </div>
            </div>
        </div>
        <div class="pl-10 md:hidden flex">
            <div v-for="(crumb,key) in breadcrumbs" class="flex items-center">
                <!-- second-last breadcrumbs item (leaf category node) -->
                <Link v-if="Object.keys(breadcrumbs).length - key === 2" :href="crumb.url" class="text-ui-text-accent">{{ crumb.title }}</Link>
            </div>
        </div>
    </div>
</template>
