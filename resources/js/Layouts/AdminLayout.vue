<script setup>
import Flash from "./Partials/Flash.vue";
import LeftMenu from "./Partials/AdminLeftMenu.vue";
</script>

<template>
    <LeftMenu/>
    <main class="ml-8 px-4">
        <Flash/>
        <slot/>
    </main>
</template>
