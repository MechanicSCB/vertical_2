<script setup>
defineProps({href:String})
</script>

<template>
    <Link :href="href" class="pl-4 h-14 flex gap-4 items-center border-b border-white">
        <slot name="icon"></slot>
        <div class="border-b border-black border-dashed">
            <slot></slot>
        </div>
    </Link>
</template>
