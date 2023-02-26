<script setup>
import Header from "./Partials/Header.vue";
import Footer from "./Partials/Footer.vue";
import Breadcrumbs from "./Partials/Breadcrumbs.vue";
import SendMessage from "./Partials/SendMessage.vue";
import Flash from "./Partials/Flash.vue";

</script>

<template>
    <Header/>
    <main class="">
        <Flash/>
        <slot/>
    </main>
    <SendMessage/>
    <Footer/>
</template>
