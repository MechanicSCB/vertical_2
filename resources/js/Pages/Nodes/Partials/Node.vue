<script setup>
defineProps({node:Object});
</script>

<template>
    <div class="mt-1 text-sm">
        {{ node.title }}
        <!-- ({{ Object.keys(node.nodes ?? {}).length }})-->
    </div>
</template>
