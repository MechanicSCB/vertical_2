<script setup>
import {useCartStore} from "@/Stores/CartStore";
import TrashIcon from "../../../Svg/TrashIcon.vue";

let props = defineProps({product: Object});
let cart = useCartStore();
</script>

<template>
    <!-- WIDE SCREEN -->
    <div class="py-3 hidden md:grid grid-cols-5 border-t items-center">
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

    <!-- MOBILE SCREEN -->
    <div class="py-4 flex flex-col md:hidden gap-2 border-t">
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

        <div class="mt-2 flex items-center gap-4">
            <div class="w-[120px] flex justify-center">
                <!-- quantity -->
                <div class="flex w-fit items-center justify-between text-xl">
                    <button @click="cart.decrement(product.id)"
                            class="mb-2 text-[35px] mr-1"
                            :class="product.quantity >1
                                        ? 'text-ui-text-primary hover:text-ui-link-hover'
                                        :'text-ui-text-secondary cursor-not-allowed'"
                    >-</button>
                    <div class="mx-3 text-center">{{ product.quantity ??= 1 }}</div>
                    <!-- <input type="text" name="quantity" class="" :value="product.quantity ??= 1">-->
                    <button @click="cart.increment(product.id)"
                            class="mb-1 text-[30px] text-ui-text-primary hover:text-ui-link-hover"
                    >+</button>
                </div>
            </div>

            <div>Цена: <span class="font-semibold">{{ product.price.toLocaleString() }} ₽</span></div>
        </div>

        <div class="flex gap-4">
            <button class="w-[120px] flex justify-center" @click="cart.removePosition(product.id)">
                <TrashIcon class="w-6 fill-ui-text-accent hover:fill-ui-text-primary"/>
            </button>

            Итого: <span class="font-semibold">{{ (product.price * product.quantity).toLocaleString() }} ₽</span>
        </div>
    </div>
</template>
