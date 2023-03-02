<script setup>
import HeartIcon from "../../../Svg/HeartIcon.vue";
import CursorArrowClickedIcon from "../../../Svg/CursorArrowClickedIcon.vue";

let props = defineProps({
    product: Object,
});

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
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

function addToCart() {
    let cart = {};

    if(typeof getCookie('cart') !== "undefined"){
        cart = JSON.parse(getCookie('cart'));
    }

    cart[props.product.id] ??= 0;
    cart[props.product.id] += props.product.quantity ?? 1;

    setCookie('cart', JSON.stringify(cart), 7);
}
</script>

<template>
    <div class="product-card h-full relative border rounded-3xl bg-ui-section overflow-hidden pb-6 flex flex-col">
        <!-- DISCOUNT/NEW/HIT -->
        <div v-if="product.id%2 === 0"
             class="absolute z-10 left-0 top-5 text-ui-text-accent_inverse text-2xl font-semibold">
            <div class="attention-label py-1 pl-3 pr-4 rounded-r-3xl bg-[#0ec6d1] transition-all">
                ХИТ
            </div>
        </div>

        <!-- FAVORITE -->
        <HeartIcon
            class="absolute right-5 top-7 fill-ui-text-light hover:fill-ui-link-hover w-10 h-10 hover:w-[42px] hover:[42px] cursor-pointer"/>

        <!-- IMAGE/TITLE LINK -->
        <Link :href="'/products/'+product.slug" class="product-link pt-2 flex flex-col">
            <img class="mx-auto h-full md:opacity-75 transition-all"
                 :src="'/storage/images/products/s220/'+product.code+'.jpg'">
            <h3 class="mx-4 text-lg font-bold hover:text-ui-link-hover transition-all">{{ product.name }}</h3>
        </Link>

        <!-- PRICE -->
        <h2 class="mt-12 mx-4 text-[40px] w-full text-center font-semibold mb-6">{{ product.price.toLocaleString() }}
            ₽</h2>

        <div class="text-ui-text-secondary flex flex-col">
            <!-- ADD TO CART -->
            <div class="mx-8 flex md:justify-between justify-center">
                <div>
                    <!-- quantity -->
                    <div class="px-5 flex border w-fit h-[60px] rounded-full items-center justify-between text-3xl">
                        <button @click="product.quantity--"
                                class="mb-3 text-[40px] mr-1 text-ui-text-primary hover:text-ui-link-hover">-
                        </button>
                        <div class="mx-1 text-center">{{ product.quantity ??= 1 }}</div>
                        <!-- <input type="text" name="quantity" class="" :value="product.quantity ??= 1">-->
                        <button @click="product.quantity++"
                                class="mb-2 text-[40px] text-ui-text-primary hover:text-ui-link-hover">+
                        </button>
                    </div>
                    <div class="mt-2 -mb-1">В наличии: <strong
                        class="font-semibold text-ui-text-accent">{{ product.availability }}</strong></div>
                    <div v-if="product.quantity > 1"><span>На сумму </span><span
                        class="font-semibold">{{ (product.price * product.quantity).toLocaleString() }} ₽</span></div>
                </div>
                <!-- add to cart -->
                <button @click="addToCart"
                        class="add-to-cart h-[60px] w-32 bg-ui-body text-ui-text-secondary hover:bg-ui-accent hover:text-ui-text-accent_inverse border-[3px] hover:border-ui-text-accent border-ui-text-secondary text-sm font-semibold rounded-[30px]">
                    В корзину
                </button>
            </div>

            <!-- BUY IN ONE CLICK -->
            <button class="mt-2 mx-auto flex hover:text-ui-link-hover group hover:fill-ui-text-accent">
                <CursorArrowClickedIcon class="w-5"/>
                Купить в один клик
            </button>

            <!-- OPT LINK -->
            <Link href="#" class="text-center" preserve-scroll>специальные&nbsp;цены для&nbsp;юридических&nbsp;лиц
            </Link>
        </div>

    </div>
</template>
<style scoped>
/* TODO: Replace to html using group */
.product-card:hover .product-link > img {
    opacity: 1;
}

.product-card:hover button.add-to-cart {
    border-color: var(--color-ui-link-text-hover);
}

.product-card:hover .attention-label {
    padding-left: 17px;
}

.cursor-arrow-icon .click-effect {
    display: none;
}

button:hover .cursor-arrow-icon .click-effect {
    display: block;
}
</style>
