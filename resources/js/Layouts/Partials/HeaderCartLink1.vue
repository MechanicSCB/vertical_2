<script setup>
import CartIcon from "../../Svg/CartIcon.vue";
// import {cart} from "../../Stores/cartStore1.js";
</script>
<template>
    <div class="flex items-center">
        <Link href="/cart">
            <CartIcon class="fill-ui-text-accent xs:w-10 w-8"/>
        </Link>
        <div class="ml-2 border w-6 h-6 text-center rounded-full flex items-center justify-center">
            <div class="mb-0.5 mr-0.5 text-ui-text-secondary">{{ Object.keys(cart).length }}</div>
        </div>
    </div>
</template>
