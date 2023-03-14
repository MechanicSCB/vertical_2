<script setup>
import Header from "./Partials/Header.vue";
import SearchArea from "./SearchArea/SearchArea.vue";
import Footer from "./Partials/Footer.vue";
import Breadcrumbs from "./Partials/Breadcrumbs.vue";
import SendMessage from "./Partials/SendMessage.vue";
import Flash from "./Partials/Flash.vue";
import {ref, provide} from "vue";

let showSearchArea = ref(false);
provide('showSearchArea', showSearchArea)
</script>

<template>
    <Header/>
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
