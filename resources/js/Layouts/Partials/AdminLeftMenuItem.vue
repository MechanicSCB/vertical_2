<script setup>
defineProps({title:String, href:String})
</script>
<template>
    <li class="flex justify-center">
        <Link :href="href" class="text-center text-ui-text-primary">{{ title }}</Link>
    </li>
</template>
