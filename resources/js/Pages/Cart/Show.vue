<script setup>
import {useCartStore} from "../../Stores/CartStore.js";
import {onMounted} from "vue";
import TrashIcon from "../../Svg/TrashIcon.vue";
import BoxIcon from "../../Svg/BoxIcon.vue";
import CursorArrowClickedIcon from "../../Svg/CursorArrowClickedIcon.vue";
import ProductCard from "../Categories/Partials/ProductCard.vue";

let props = defineProps({relatedProducts: Object});
let cart = useCartStore();

onMounted(() => {
    cart.getProducts();
});
</script>

<template>
    <Head title="Моя корзина"/>
    <div class="mx-auto px-9 max-w-[1656px]">
        <div class="mt-3 flex gap-2">
            <button class="w-10 h-10" v-for="relatedProduct in relatedProducts" @click="cart.add(relatedProduct)">
                <img class="mx-auto max-w-full max-h-full"
                     :src="'/storage/images/products/s220/'+relatedProduct.code+'.jpg'"/>
            </button>
        </div>

        <!-- SHOW CART ITEMS -->
        <div v-if="Object.keys(cart.products).length">
            <!-- Header/Clear Cart -->
            <div class="my-12 flex justify-between">
                <h1 class="text-3xl font-semibold">Моя корзина</h1>

                <!-- Clear cart -->
                <button @click="cart.clear()" class="flex items-center gap-3 group">
                    <TrashIcon class="fill-ui-text-secondary w-6 group-hover:fill-ui-text-accent"/>
                    <div
                        class="border-b border-dashed border-ui-text-accent font-semibold text-ui-text-accent group-hover:border-ui-body">
                        Очистить корзину
                    </div>
                </button>
            </div>

            <!-- Cart Table -->
            <div class="flex flex-col xl:flex-row gap-x-8">
                <!-- Cart Products Table -->
                <div class="mb-24 w-full xl:w-2/3 2xl:w-3/4">
                    <div class="grid grid-cols-5 text-ui-text-secondary mb-9">
                        <span class="col-span-2 ml-[136px]">Товар</span>
                        <span class="text-center">Цена</span>
                        <span class="text-center">Кол-во</span>
                        <span class="text-center">Итого</span>
                    </div>
                    <div v-for="product in cart.products" class="py-3 grid grid-cols-5 border-t items-center">
                        <Link :href="route('products.show', product.slug)"
                              class="group col-span-2 flex">
                            <div class="ml-4 w-[82px] h-[82px] shrink-0 flex items-center mr-10">
                                <img class="mx-auto max-w-[82px] max-h-[82px]" :src="'/storage/images/products/s220/'+product.code+'.jpg'" :alt="product.name"/>
                            </div>
                            <div class=" flex flex-col">
                                <span class="font-bold text-[14px] group-hover:text-ui-link-hover">{{
                                        product.name
                                    }}</span>
                                <span class="text-sm text-ui-text-secondary">Код товара: {{ product.code }}</span>
                            </div>
                        </Link>
                        <div class="font-semibold text-center">{{ product.price.toLocaleString() }} ₽</div>
                        <div>
                            <!-- quantity -->
                            <div
                                class="px-5 flex border w-fit h-[43px] rounded-full items-center justify-between text-3xl">
                                <button @click="cart.decrement(product.id)"
                                        class="mb-3 text-[40px] mr-1"
                                        :class="product.quantity >1
                                        ? 'text-ui-text-primary hover:text-ui-link-hover'
                                        :'text-ui-text-secondary cursor-not-allowed'"
                                >-</button>
                                <div class="mx-3 text-center">{{ product.quantity ??= 1 }}</div>
                                <!-- <input type="text" name="quantity" class="" :value="product.quantity ??= 1">-->
                                <button @click="cart.increment(product.id)"
                                        class="mb-2 text-[40px] text-ui-text-primary hover:text-ui-link-hover"
                                >+</button>
                            </div>
                        </div>
                        <div class="font-semibold justify-end flex gap-3">
                            {{ (product.price * product.quantity).toLocaleString() }} ₽
                            <button @click="cart.removePosition(product.id)">
                                <TrashIcon class="w-6 fill-ui-text-accent hover:fill-ui-text-primary"/>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Place An Order -->
                <div class="pt-10 mx-auto max-w-[560px] px-7 border rounded-3xl pb-5 w-full xl:w-1/3 2xl:w-1/4">
                    <div class="text-xl font-semibold">{{ cart.length }} товара</div>
                    <!-- Price Rows -->
                    <div class="mt-6 font-semibold">
                        <div class="flex justify-between border-b border-ui-text-primary pb-4">
                            <div class="font-normal">Сумма</div>
                            <div class="">{{ cart.orderSum.toLocaleString() }} ₽</div>
                        </div>
                        <div class="mt-2 flex justify-between border-b border-ui-text-primary pb-4">
                            <div class="text-ui-text-accent">Скидка</div>
                            <div class="">{{ ((1 - cart.discount) * 100).toLocaleString() }}%</div>
                        </div>
                        <div class="mt-2 flex justify-between border-b border-ui-text-primary pb-4">
                            <div class="">Итого</div>
                            <div class="">{{ (cart.orderSum * cart.discount).toLocaleString() }} ₽</div>
                        </div>
                    </div>

                    <!-- without delivery -->
                    <div class="mt-8 flex gap-3">
                        <BoxIcon class="w-6 fill-ui-text-accent"/>
                        Стоимость указана без учета доставки
                    </div>

                    <button @click="placeOrder"
                            class="mt-6 h-[60px] block mx-auto px-8 bg-ui-body text-ui-text-accent hover:bg-ui-accent hover:text-ui-text-accent_inverse border-[3px] border-ui-text-accent font-semibold rounded-[30px]">
                        Оформить заказ
                    </button>

                    <!-- BUY IN ONE CLICK -->
                    <button
                        class="mt-6 mx-auto flex fill-ui-text-secondary text-ui-text-secondary hover:text-ui-link-hover group hover:fill-ui-text-accent">
                        <CursorArrowClickedIcon class="w-5 mr-2"/>
                        Купить в один клик
                    </button>

                    <!-- Share cart -->
                    <div class="mt-2 mx-auto w-fit text-ui-text-secondary">Поделиться корзиной</div>
                </div>
            </div>
        </div>

        <!-- THE CART IS EMPTY -->
        <div v-else class="flex mb-32">
            <!-- AMAZING IMAGE  -->
            <div class="md:w-1/2 w-full"></div>

            <!-- EMPTY CART NOTIFICATION -->
            <div class="md:w-1/2 w-full pt-32">
                <h1 class="lg:text-5xl text-4xl font-bold flex flex-wrap pr-20">
                    <span class="mr-3">В корзине</span>
                    <span class="text-ui-text-accent">пусто</span></h1>
                <div class="mt-5 text-ui-text-secondary text-lg">Перейдите в каталог для выбора товаров</div>

                <Link :href="route('categories.index')"
                      class="mt-16 h-[60px] flex w-fit items-center px-14 bg-ui-body text-ui-text-accent hover:bg-ui-accent hover:text-ui-text-accent_inverse border-[3px] border-ui-text-accent font-semibold rounded-[30px]"
                >
                    Перейти в каталог
                </Link>
            </div>
        </div>

        <!-- TEST ADD TO CART -->
        <div class="mt-10 grid grid-cols-3 gap-2">
            <ProductCard v-for="relatedProduct in relatedProducts" :product="relatedProduct"/>
        </div>
    </div>
</template>
