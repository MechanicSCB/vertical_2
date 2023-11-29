<script setup>
import LogoFull from "@/Svg/LogoFull.vue";
import HeartIcon from "@/Svg/HeartIcon.vue";
import CartIcon from "@/Svg/CartIcon.vue";
import MagnifyIcon from "@/Svg/MagnifyIcon.vue";
import GeolocationIcon from "@/Svg/GeolocationIcon.vue";
import WhatsupHeaderLogo from "@/Svg/WhatsupHeaderLogo.vue";
import CatalogRowsIcon from "@/Svg/CatalogRowsIcon.vue";
import {inject, onMounted, ref} from "vue";
import {useCartStore} from "@/Stores/CartStore.js";

let cart = useCartStore();
cart.getProducts();

let showSearchArea = inject('showSearchArea');
let showMobileMenu = inject('showMobileMenu');

let isScrolled = ref(false);

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

let handleScroll = function (event) {
    if (window.scrollY > 150) {
        isScrolled.value = true;
    } else {
        isScrolled.value = false;
    }
}
</script>
<template>
    <!-- No Scroll -->
    <div class="relative xl:block hidden z-[60] top-0 mt-[25px] h-[145px] bg-ui-body">
        <div class="mx-auto px-9 w-full max-w-[1656px]">
        <!-- Top Row -->
        <div class="mt-5 flex justify-between text-ui-text-secondary items-center">
            <!-- Geolocation -->
            <div class="flex cursor-pointer group">
                <GeolocationIcon class="w-7 fill-ui-text-secondary"/>
                <div class="ml-2 h-fit text-ui-text-accent border-b border-dashed border-ui-text-accent group-hover:border-0"
                >Москва
                </div>
            </div>

            <!-- Menu -->
            <div class="flex gap-6">
                <Link href="" class="hover:text-ui-link-hover">Акции</Link>
                <Link href="" class="hover:text-ui-link-hover">Оптовым клиентам</Link>
                <Link href="" class="hover:text-ui-link-hover">Доставка</Link>
                <Link href="" class="hover:text-ui-link-hover">Возврат</Link>
                <Link href="" class="hover:text-ui-link-hover">Контакты</Link>
                <Link href="" class="hover:text-ui-link-hover">Обратная связь</Link>
            </div>

            <!-- Header Contacts -->
            <div class="-mt-2 w-[435px] flex justify-between">
                <!-- Phone -->
                <div>
                    <div>9:00 — 21:00, ежедневно</div>
                    <div class="-my-1.5 text-ui-link-hover text-2xl font-bold">8 (499) 288-57-23</div>
                    <button class="border-b border-dashed hover:text-ui-link-hover">заказать звонок</button>
                </div>

                <!-- Email -->
                <div class="mt-2 h-fit font-semibold border-b border-dashed border-ui-text-accent text-ui-link-hover hover:border-0">
                    contact@vertical.ru
                </div>

                <!-- Whatsup -->
                <WhatsupHeaderLogo class="w-10 cursor-pointer hover:w-12 hover:-mx-1 transition-all"/>
            </div>
        </div>

        <!-- Bottom Row -->
        <div class="mt-2 flex items-center justify-between">
            <!-- Logo -->
            <LogoFull class="shrink-0"/>

            <!-- Catalog Button -->
            <Link class="ml-12 shrink-0" :href="route('categories.index')">
                <button class="
                    flex items-center justify-center
                    h-[60px] w-[180px] border-[3px] rounded-[30px] text-lg font-semibold
                    border-ui-accent bg-ui-accent text-ui-text-accent_inverse
                    hover:bg-ui-body hover:text-ui-link-hover
                    fill-ui-text-accent_inverse hover:fill-ui-text-accent
                ">
                    <CatalogRowsIcon class="w-6"/>
                    Каталог
                </button>
            </Link>

            <!-- Search Input -->
            <div @click="showSearchArea=!showSearchArea" class="ml-7 flex items-center border rounded-[30px] py-2 px-5 w-full">
                <MagnifyIcon class="fill-ui-text-accent"/>
                <input class="border-0 w-full focus:ring-0" type="text" placeholder="Smart-поиск среди 50 480 товаров">
            </div>

            <!-- Cart Info -->
            <div class="ml-16 shrink-0 flex items-center">
                <div class="w-[75px]">
                    <HeartIcon class="w-10 fill-ui-text-accent"/>
                </div>

                <Link :href="route('cart.show')" class="flex items-center">
                    <div class="cursor-pointer flex w-[60px] h-[60px] bg-ui-accent rounded-full items-center justify-center">
                        <CartIcon class="fill-ui-text-accent_inverse"/>
                    </div>

                    <div v-if="cart.positionsCount" class="ml-4 text-sm text-ui-text-secondary border-b border-ui-text-accent">
                        {{ cart.positionsCount }} товара
                        <span class="font-bold">{{ cart.orderSum.toLocaleString() }} ₽</span>
                    </div>
                </Link>
            </div>

        </div>
    </div>
    </div>

    <!-- TODO ref -->
    <!-- Fixed -->
    <div v-if="isScrolled" class="fixed z-[60] xl:block hidden top-0 w-full h-[106px] bg-ui-body shadow-lg">
        <div class="mx-auto px-9 w-full max-w-[1656px]">
            <!-- Bottom Row -->
            <div class="mt-5 flex items-center justify-between">
                <!-- Logo -->
                <LogoFull class="shrink-0"/>

                <!-- Catalog Button -->
                <Link class="ml-12 shrink-0" :href="route('categories.index')">
                    <button class="
                    flex items-center justify-center
                    h-[60px] w-[180px] border-[3px] rounded-[30px] text-lg font-semibold
                    border-ui-accent bg-ui-accent text-ui-text-accent_inverse
                    hover:bg-ui-body hover:text-ui-link-hover
                    fill-ui-text-accent_inverse hover:fill-ui-text-accent
                ">
                        <CatalogRowsIcon class="w-6"/>
                        Каталог
                    </button>
                </Link>

                <!-- Search Input -->
                <div @click="showSearchArea=!showSearchArea" class="ml-7 flex items-center border rounded-[30px] py-2 px-5 w-full">
                    <MagnifyIcon class="fill-ui-text-accent"/>
                    <input class="border-0 w-full focus:ring-0" type="text" placeholder="Smart-поиск среди 50 480 товаров">
                </div>

                <!-- Header Contacts -->
                <div class="ml-3 shrink-0  flex justify-between text-ui-text-secondary">
                    <!-- Phone -->
                    <div>
                        <div>9:00 — 21:00, ежедневно</div>
                        <div class="-my-1.5 text-ui-link-hover text-2xl font-bold">8 (499) 288-57-23</div>
                        <button class="border-b border-dashed hover:text-ui-link-hover">заказать звонок</button>
                    </div>
                </div>

                <!-- Cart Info -->
                <div class="ml-16 shrink-0 flex items-center">
                    <div class="w-[75px]">
                        <HeartIcon class="w-10 fill-ui-text-accent"/>
                    </div>

                    <Link :href="route('cart.show')" class="flex items-center">
                        <div class="cursor-pointer flex w-[60px] h-[60px] bg-ui-accent rounded-full items-center justify-center">
                            <CartIcon class="fill-ui-text-accent_inverse"/>
                        </div>
                        <div class="ml-2 border border-ui-text-secondary w-6 h-6 text-center rounded-full flex items-center justify-center">
                            <div class="mb-0.5 mr-0.5 text-ui-text-secondary font-semibold">{{ cart.positionsCount }}</div>
                        </div>
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
