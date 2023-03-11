<script setup>
import LeftMenuItem from "./LeftMenuItem.vue";
import LogoMin from "../../Svg/LogoMin.vue";
</script>

<template>
    <nav class="fixed top-0 w-8 h-full text-white bg-ui-secondary">
        <Link href="/"><LogoMin class="w-full"/></Link>
        <ul class="flex flex-col">
            <LeftMenuItem :href="route('admin.categories.index')" title="C"/>
            <LeftMenuItem :href="route('admin.nodes.index')" title="N"/>
            <LeftMenuItem :href="route('admin.products.index')" title="P"/>
            <LeftMenuItem :href="route('admin.orders.index')" title="O"/>
        </ul>
    </nav>
</template>
