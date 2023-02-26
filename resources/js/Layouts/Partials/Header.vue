<script setup>
import LogoMin from "../../Svg/LogoMin.vue";
import LogoFull from "../../Svg/LogoFull.vue";
import HamburgerButton from "./HamburgerButton.vue";
import HeartIcon from "../../Svg/HeartIcon.vue";
import CartIcon from "../../Svg/CartIcon.vue";
import MagnifyIcon from "../../Svg/MagnifyIcon.vue";
import PhoneIcon from "../../Svg/PhoneIcon.vue";

</script>
<template>
    <!--    chock    -->
    <div class="h-14"></div>
    <div class="fixed top-0 w-full bg-ui-section flex justify-between items-center shadow-lg h-14 py-3 md:px-10 px-2 mb-4">
        <div class="left_header flex items-center xs:gap-6 gap-2">
            <HamburgerButton class="mr-3"/>
            <Link href="/drag">
                <HeartIcon class="fill-ui-text-accent xs:w-10 w-8"/>
            </Link>
            <div class="flex items-center">
                <Link href="/tree">
                    <CartIcon class="fill-ui-text-accent xs:w-10 w-8"/>
                </Link>
                <div class="ml-2 border w-6 h-6 text-center rounded-full flex items-center justify-center">
                    <div class="mb-0.5 mr-0.5 text-ui-text-secondary">1</div>
                </div>
            </div>
        </div>
        <Link href="/" class="center_header mr-20">
            <LogoMin class="block md:hidden"/>
            <LogoFull class="hidden md:block"/>
        </Link>
        <div class="right_header flex xs:gap-6 gap-2">
            <Link href="/categories/create">
                <MagnifyIcon class="fill-ui-text-secondary"/>
            </Link>
            <Link :href="route('categories.edit',8)">
                <PhoneIcon class="fill-ui-text-accent"/>
            </Link>
        </div>
    </div>
</template>
