<script setup>
import Header from "@/Layouts/Partials/Header.vue";
import MobileMenu from "@/Layouts/Partials/MobileMenu.vue";
import SearchArea from "@/Layouts/SearchArea/SearchArea.vue";
import Footer from "@/Layouts/Partials/Footer.vue";
import SendMessage from "@/Layouts/Partials/SendMessage.vue";
import Flash from "@/Layouts/Partials/Flash.vue";
import HeaderSmall from "@/Layouts/Partials/HeaderSmall.vue";
import {ref, provide} from "vue";

let showSearchArea = ref(false);
let showMobileMenu = ref(false);
provide('showSearchArea', showSearchArea)
provide('showMobileMenu', showMobileMenu)
</script>

<template>
    <Header/>
    <HeaderSmall/>
    <MobileMenu v-if="showMobileMenu"/>
    <SearchArea v-show="showSearchArea"/>
    <div :class="{'fixed':showSearchArea}">
        <main class="">
            <Flash/>
            <slot/>
        </main>
        <SendMessage/>
        <Footer/>
    </div>
</template>
