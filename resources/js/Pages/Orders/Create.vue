<script setup>
import {useCartStore} from "../../Stores/CartStore.js";
import {useForm} from "@inertiajs/vue3";
import FormErrors from "../../Layouts/Partials/FormErrors.vue";

let cart = useCartStore();
let form = useForm({
    customer_type:'',
    name:'',
    phone:'',
    email:'',
    whatsapp:false,
    cart:{}
});


function submit(){
    form.cart.products = cart.products;
    form.cart.discount = cart.discount;
    form.cart.orderSum = cart.orderSum;
    form.post(route('orders.store'));
}
</script>
<template>
    <div class="mx-auto px-9 max-w-[1656px] pb-12">
        <h1 class="mt-10 text-4xl font-semibold">Оформление заказа</h1>

        <!-- Steps -->
        <div class="mt-16 flex font-semibold text-ui-text-secondary justify-between">
            <div class="text-ui-text-accent border-b-4 w-full">
                <div class="h-12">Контактные данные</div>
                <div class="-mb-[12px] w-5 h-5 rounded-full bg-ui-accent"></div>
            </div>
            <div class="border-b-4 w-full">
                <div class="h-12">Город доставки</div>
                <div class="-mb-[12px] w-5 h-5 rounded-full bg-ui-secondary"></div>
            </div>
            <div class="border-b-4 w-full">
                <div class="h-12">Варианты доставки</div>
                <div class="-mb-[12px] w-5 h-5 rounded-full bg-ui-secondary"></div>
            </div>
            <div class="border-b-4 w-full">
                <div class="flex gap-2 w-full justify-between">
                    <div class="h-12">Оплата</div><div class="h-12">Финальный шаг</div>
                </div>
                <div class="-mb-[12px] flex justify-between">
                    <div class="w-5 h-5 rounded-full bg-ui-secondary"></div>
                    <div class="w-5 h-5 rounded-full bg-ui-secondary"></div>
                </div>
            </div>
        </div>

        <div class="mt-12 flex">
            <!-- Customer Form -->
            <form @submit.prevent="submit" class="w-2/3 pr-10">
                <!--  Errors messages  -->
                <FormErrors class="mb-5" :form="form"/>

                <h1 class="text-3xl font-semibold">Выберите тип плательщика</h1>

                <div class="mt-6 flex flex-col text-lg gap-y-6">
                    <label class="flex items-center cursor-pointer"><input class="text-ui-text-accent !ring-0" type="radio" v-model="form.customer_type" value="person" name="customer_type"><span class="ml-5">Физическое лицо</span></label>
                    <label class="flex items-center cursor-pointer"><input class="text-ui-text-accent !ring-0" type="radio" v-model="form.customer_type" value="company" name="customer_type"><span class="ml-5">Юридическое лицо</span></label>
                </div>

                <h1 class="my-5 text-3xl font-semibold">Контактные данные</h1>

                <div class="flex flex-col gap-6">
                    <input required v-model="form.name" class="w-full rounded-[30px] !border-ui-text-light !ring-0 1bg-ui-accent_light px-8 py-4 text-lg" type="text" placeholder="Имя">
                    <input  required v-model="form.phone" class="w-full rounded-[30px] !border-ui-text-light !ring-0 1bg-ui-accent_light px-8 py-4 text-lg" type="text" placeholder="Телефон">
                    <input v-model="form.email" class="w-full rounded-[30px] !border-ui-text-light !ring-0 1bg-ui-accent_light px-8 py-4 text-lg" type="text" placeholder="Email">
                    <label class="flex items-top text-lg">
                        <input v-model="form.whatsapp" class="mt-1" type="checkbox">
                        <div class="ml-5">Не хочу, чтобы мне звонили. Свяжитесь со мной по WhatsApp !</div>
                    </label>
                </div>
                <!-- Buttons -->
                <div class="mt-8">
                    <div class="text-sm text-ui-text-secondary">Нажимая на кнопку «Далее», я даю на обработку моих персональных данных в  соответствии с политикой информационной безопасности. Мы не используем данные и не присылаем рассылки </div>
                    <div class="mt-6 flex gap-x-8">
                        <Link :href="route('cart.show')"
                              class="flex w-fit items-center h-[60px] px-8 bg-ui-body text-ui-text-accent hover:bg-ui-accent hover:text-ui-text-accent_inverse border-[3px] border-ui-text-accent font-semibold rounded-[30px]">
                            В корзину
                        </Link>
                        <div @click="submit"
                              class="flex w-fit items-center h-[60px] px-8 hover:bg-ui-body hover:text-ui-text-accent bg-ui-accent text-ui-text-accent_inverse border-[3px] border-ui-text-accent font-semibold rounded-[30px] cursor-pointer">
                            Далее
                        </div>
                    </div>
                </div>
            </form>

            <!-- Order Card -->
            <div class="sticky top-0 pt-10 h-fit max-w-[360px] px-7 border rounded-3xl pb-5 w-full md:w-1/3 2xl:w-1/4">
                <div class="text-xl font-semibold">Товаров: {{ Object.keys(cart.products).length }}</div>

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
                    <div class="mt-2 flex justify-between pb-4">
                        <div class="">Итого</div>
                        <div class="">{{ (cart.orderSum * cart.discount).toLocaleString() }} ₽</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
