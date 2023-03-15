<script setup>
import {useCartStore} from "../../Stores/CartStore.js";
import Breadcrumbs from "../../Layouts/Partials/Breadcrumbs.vue";
import CheckedIcon from "../../Svg/CheckedIcon.vue";
import CursorArrowClickedIcon from "../../Svg/CursorArrowClickedIcon.vue";
import ProductTabs from "./Partials/ProductTabs.vue";
import ProductImageBlock from "./Partials/ProductImageBlock.vue";

let props = defineProps({
    breadcrumbs: Object,
    product: Object,
});

let cart = useCartStore();

function decrementQuantity(){
    if(props.product.quantity > 1){
        props.product.quantity--;
    }
}
</script>
<template>
    <Head :title="product.name + ' цена - купить в интернет-магазине Вертикаль'"/>
    <Breadcrumbs :breadcrumbs="breadcrumbs"/>


    <div class="mt-6 mx-auto px-9 max-w-[1656px]">
        <!--  IMAGE/BUY CARD  -->
        <div class="mt-10 flex md:flex-row flex-col gap-8">
            <!--  LEFT  -->
            <div class="xl:w-1/3 md:w-1/2 w-full">
                <ProductImageBlock :product="product"/>
            </div>

            <!--  RIGHT  -->
            <div class="xl:w-2/3 md:w-1/2 w-full">
                <h1 class="text-5xl font-semibold" :title="product.name">{{ product.name }}</h1>

                <!-- CODE/RATING -->
                <div class="mt-3 flex flex-col text-ui-text-secondary">
                    <div>Код товара: {{ product.code }}</div>

                    <div class="mt-3 flex gap-2">
                        <div class="flex"><img v-for="star in [1,1,1,1,1]" src="/images/star.png" alt=""></div>
                        <span class="review-label">{{ product.reply_count }} отзыв </span>
                    </div>
                </div>

                <!-- BUY CARD -->
                <div class="mt-10 px-6 py-6 rounded-3xl bg-ui-light text-ui-text-secondary">
                    <!--  availability  -->
                    <div class="flex items-center text-ui-text-accent gap-6">
                        <CheckedIcon class="w-4 fill-ui-text-accent"/>
                        {{ product.availability !== '' ? 'В наличии на складе' : 'Товара нет в наличии' }}
                    </div>

                    <!-- PRICE/BUY BLOCK -->
                    <div class="mt-10 flex flex-wrap gap-x-4">
                        <!-- PRICE -->
                        <h2 class="text-[40px] font-semibold text-ui-text-primary">{{ product.price.toLocaleString() }} ₽</h2>

                        <!-- ADD TO CART -->
                        <div class="mt-4 flex gap-x-3">
                            <!-- quantity/availability -->
                            <div>
                                <!-- quantity -->
                                <div class="px-5 flex border bg-ui-body w-fit h-[60px] rounded-full items-center justify-between text-3xl">
                                    <button @click="decrementQuantity" class="mb-3 text-[40px] mr-1 text-ui-text-primary hover:text-ui-link-hover"
                                    >-</button>
                                    <div class="mx-5 text-center">{{ product.quantity ??= 1 }}</div>
                                    <button @click="product.quantity++" class="mb-2 text-[40px] text-ui-text-primary hover:text-ui-link-hover">+</button>
                                </div>
                                <div class="mt-2 -mb-1">В наличии: <strong class="font-semibold text-ui-text-accent">{{ product.availability }}</strong></div>
                                <div v-if="product.quantity > 1"><span>На сумму </span><span class="font-semibold">{{ (product.price * product.quantity).toLocaleString() }} ₽</span></div>
                            </div>

                            <!-- add to cart -->
                            <button  @click="cart.add(product,product.quantity)"
                                class="add-to-cart h-[60px] w-32 bg-ui-accent hover:bg-ui-body text-ui-text-accent_inverse hover:text-ui-text-accent border-[3px] border-ui-text-accent text-sm font-semibold rounded-[30px]"
                            >В корзину</button>
                        </div>
                    </div>

                    <!-- OPT LINK -->
                    <Link href="#" class="mt-6 flex text-center" preserve-scroll>специальные&nbsp;цены для&nbsp;юридических&nbsp;лиц</Link>
                </div>

                <!--  FIND IT CHEAPER/BUY IN ONE CLICK -->
                <div class="mt-10 mx-auto flex gap-6 justify-center">
                    <button class="flex hover:text-ui-link-hover hover:fill-ui-link-hover text-ui-text-secondary fill-ui-text-secondary">
                        Нашли дешевле?
                    </button>
                    <button class="flex hover:text-ui-link-hover hover:fill-ui-link-hover text-ui-text-secondary fill-ui-text-secondary">
                        <CursorArrowClickedIcon class="w-5"/>
                        Купить в один клик
                    </button>
                </div>

            </div>
        </div>

        <!-- PRODUCT TABS  -->
        <ProductTabs :product="product"/>
    </div>
</template>
