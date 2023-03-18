<script setup>
import LogoMin from "../../Svg/LogoMin.vue";
import LogoFull from "../../Svg/LogoFull.vue";
import HamburgerButton from "./HamburgerButton.vue";
import HeartIcon from "../../Svg/HeartIcon.vue";
import CartIcon from "../../Svg/CartIcon.vue";
import MagnifyIcon from "../../Svg/MagnifyIcon.vue";
import PhoneIcon from "../../Svg/PhoneIcon.vue";
import HeaderCartLink from "./HeaderCartLink.vue";
import {inject} from "vue";

let showSearchArea = inject('showSearchArea');
let showMobileMenu = inject('showMobileMenu');
</script>
<template>
    <!--    chock    -->
    <div class="h-14"></div>
    <div class="fixed xl:hidden z-50 top-0 w-full bg-ui-section flex justify-between items-center shadow-lg h-14 py-3 md:px-10 px-2 mb-4">
        <div class="left_header flex items-center xs:gap-6 gap-2">
            <HamburgerButton @click="showMobileMenu=!showMobileMenu" class="mr-3"/>
            <Link :href="route('admin.index')">
                <HeartIcon class="fill-ui-text-accent xs:w-10 w-8"/>
            </Link>
            <HeaderCartLink/>
        </div>
        <Link href="/" class="center_header mr-20">
            <LogoMin class="block md:hidden"/>
            <LogoFull class="hidden md:block"/>
        </Link>
        <div class="right_header flex xs:gap-6 gap-2">
            <button>
                <MagnifyIcon @click="showSearchArea=!showSearchArea"  class="fill-ui-text-secondary"/>
            </button>
            <Link href="/tree">
                <PhoneIcon class="fill-ui-text-accent"/>
            </Link>
        </div>
    </div>
</template>
