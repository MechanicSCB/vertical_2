<script setup>
import {inject} from "vue";

let props = defineProps({tabName: Object});

let activeTab = inject('activeTab');
</script>

<template>
    <li @click="activeTab=tabName"
        :class="activeTab===tabName?'text-ui-text-accent':'border-b border-ui-text-accent border-dashed cursor-pointer'"
    >
        <slot/>
    </li>
</template>

<style scoped>

</style>
