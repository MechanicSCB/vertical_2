<script setup>
import {inject} from "vue";

let showMobileMenu = inject('showMobileMenu')
</script>

<template>
    <div class="hamburger cursor-pointer flex flex-col w-7 gap-y-1" :class="{'menu-open': showMobileMenu}">
        <i/>
        <i/>
        <i/>
    </div>
</template>

<style>
.hamburger i {
    transition: all .2s ease;
    width: 28px;
    height: 4px;
    background: var(--color-ui-text-accent);
    border-radius: 5px;
}

.menu-open.hamburger i:first-child {
    transform: translateY(8px) rotate(45deg);
}

.menu-open.hamburger i:nth-child(2) {
    transform: translateX(-100%);
    opacity: 0;
}

.menu-open.hamburger i:last-child {
    transform: translateY(-8px) rotate(-45deg);
}
</style>
