<script setup>
import CartIcon from "../../Svg/CartIcon.vue";
import {useCartStore} from "../../Stores/CartStore.js";

let cart = useCartStore();
</script>
<template>
    <div class="flex items-center">
        <Link href="/cart">
            <CartIcon class="fill-ui-text-accent xs:w-10 w-8"/>
        </Link>
        <div class="ml-2 border w-6 h-6 text-center rounded-full flex items-center justify-center">
            <div class="mb-0.5 mr-0.5 text-ui-text-secondary">{{ cart.positionsCount }}</div>
        </div>
        <!-- TODO TEMP!!! -->
        <div class="ml-2">{{ cart.orderSum }}</div>
    </div>
</template>
