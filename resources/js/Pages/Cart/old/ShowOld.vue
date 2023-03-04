<script setup>
import TrashIcon from "../../../Svg/TrashIcon.vue";
import BoxIcon from "../../../Svg/BoxIcon.vue";
import CursorArrowClickedIcon from "../../../Svg/CursorArrowClickedIcon.vue";

let props = defineProps({products: Object});

function getOrderSum() {
    let orderSum = 0;

    for (let productId of Object.keys(props.products)) {
        orderSum += props.products[productId].price * props.products[productId].quantity;
    }

    return orderSum;
}

function getDiscount() {
    return 0.95;
}

function incrementQuantity(product) {
    product.quantity++;
    updateCookie();
}

function decrementQuantity(product) {
    product.quantity--;
    updateCookie();
}

function removeItemFromCart(productId) {
    delete props.products[productId];
    updateCookie();
}

function clearCart() {
    for (let productId of Object.keys(props.products)) {
        delete props.products[productId];
    }

    updateCookie();
}

function updateCookie() {
    let cart = {};

    for (let productId of Object.keys(props.products)) {
        cart[productId] = props.products[productId].quantity;
    }

    setCookie('cart', JSON.stringify(cart), 7);
}

function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/;SameSite=Lax";
}
</script>

<template>
    <div class="mx-auto px-9 max-w-[1656px]">
        <!-- SHOW CART ITEMS -->
        <div v-if="Object.keys(products).length">
            <div class="my-12 flex justify-between">
                <h1 class="text-3xl font-semibold">Моя корзина</h1>
                <!-- Clear cart -->
                <button @click="clearCart" class="flex items-center gap-3 group">
                    <TrashIcon class="fill-ui-text-secondary w-6 group-hover:fill-ui-text-accent"/>
                    <div
                        class="border-b border-dashed border-ui-text-accent font-semibold text-ui-text-accent group-hover:border-ui-body">
                        Очистить корзину
                    </div>
                </button>
            </div>

            <div class="flex flex-col xl:flex-row gap-x-8">
                <!-- Cart Products Table -->
                <div class="mb-24 w-full xl:w-2/3 2xl:w-3/4">
                    <div class="grid grid-cols-5 text-ui-text-secondary mb-9">
                        <span></span>
                        <span>Товар</span>
                        <span class="text-center">Цена</span>
                        <span class="text-center">Кол-во</span>
                        <span class="text-center">Итого</span>
                    </div>
                    <div v-for="product in products" class="py-3 grid grid-cols-5 border-t items-center">
                        <Link :href="route('products.show', product.slug)"
                              class="group col-span-2 grid-cols-2 grid grid-cols-2">
                            <div class="ml-4 w-[82px] h-[82px]">
                                <img class="mx-auto max-w-full max-h-full"
                                     :src="'/storage/images/products/s220/'+product.code+'.jpg'" :alt="product.name"/>
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
                                <button @click="decrementQuantity(product)"
                                        class="mb-3 text-[40px] mr-1 text-ui-text-primary hover:text-ui-link-hover">-
                                </button>
                                <div class="mx-3 text-center">{{ product.quantity ??= 1 }}</div>
                                <!-- <input type="text" name="quantity" class="" :value="product.quantity ??= 1">-->
                                <button @click="incrementQuantity(product)"
                                        class="mb-2 text-[40px] text-ui-text-primary hover:text-ui-link-hover">+
                                </button>
                            </div>
                        </div>
                        <div class="font-semibold justify-end flex gap-3">
                            {{ (product.price * product.quantity).toLocaleString() }} ₽
                            <button @click="removeItemFromCart(product.id)">
                                <TrashIcon class="w-6 fill-ui-text-accent hover:fill-ui-text-primary"/>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Place An Order -->
                <div class="pt-10 mx-auto max-w-[560px] px-7 border rounded-3xl pb-5 w-full xl:w-1/3 2xl:w-1/4">
                    <div class="text-xl font-semibold">{{ products.length }} товара</div>
                    <!-- Price Rows -->
                    <div class="mt-6 font-semibold">
                        <div class="flex justify-between border-b border-ui-text-primary pb-4">
                            <div class="font-normal">Сумма</div>
                            <div class="">{{ getOrderSum().toLocaleString() }} ₽</div>
                        </div>
                        <div class="mt-2 flex justify-between border-b border-ui-text-primary pb-4">
                            <div class="">Итого</div>
                            <div class="">{{ (getOrderSum() * getDiscount()).toLocaleString() }} ₽</div>
                        </div>
                    </div>

                    <!-- without delivery -->
                    <div class="mt-8 flex gap-3">
                        <BoxIcon class="w-6 fill-ui-text-accent"/>
                        Стоимость указана без учета доставки
                    </div>

                    <button @click="addToCart"
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
    </div>
</template>
