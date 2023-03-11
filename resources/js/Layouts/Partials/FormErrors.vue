<script setup>
defineProps({form: Object});
</script>

<template>
    <div v-if="Object.keys(form.errors).length !== 0" class="px-2 py-1 w-fit bg-red-600 border border-[rgba(0,0,0,0.1)] text-white rounded">
        <p v-for="error in form.errors">{{ error }}</p>
    </div>
</template>
