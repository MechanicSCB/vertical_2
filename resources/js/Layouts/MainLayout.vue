<script setup>
import Header from "./Partials/Header.vue";
import MobileMenu from "./Partials/MobileMenu.vue";
import SearchArea from "./SearchArea/SearchArea.vue";
import Footer from "./Partials/Footer.vue";
import Breadcrumbs from "./Partials/Breadcrumbs.vue";
import SendMessage from "./Partials/SendMessage.vue";
import Flash from "./Partials/Flash.vue";
import HeaderSmall from "./Partials/HeaderSmall.vue";
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
