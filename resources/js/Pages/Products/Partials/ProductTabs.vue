<script setup>
import {provide, ref} from "vue";
import TabBookmark from "./TabBookmark.vue";

let props = defineProps({product: Object});

let activeTab = ref('description');

provide('activeTab', activeTab)
</script>

<template>
    <!-- PRODUCT TABS  -->
    <div class="mt-20">
        <!-- BOOKMARKS  -->
        <ul class="flex text-[23px] font-semibold gap-x-7">
            <TabBookmark tabName="description">Описание</TabBookmark>
            <TabBookmark tabName="params">Характеристики</TabBookmark>
            <TabBookmark tabName="warranties">Гарантия</TabBookmark>
            <TabBookmark tabName="shipment">Доставка</TabBookmark>
            <TabBookmark tabName="payments">Оплата</TabBookmark>
            <TabBookmark tabName="replies">Отзывы</TabBookmark>
        </ul>

        <!-- TAB CONTENTS  -->
        <div class="mt-9 text-ui-text-secondary text-[15px] font-semibold">
            <div v-if="activeTab==='description'" v-html="product.description"/>
            <div v-if="activeTab==='params'">
                <div v-for="(value,param) in JSON.parse(product.params)"
                     class="h-[54px] border-t flex items-center grid grid-cols-2 hover:bg-ui-light"
                >
                    <span class="text-ui-text-primary font-bold">{{ param }}</span><span>{{ value }}</span>
                </div>
            </div>
            <div v-if="activeTab==='warranties'">
                Если до истечения гарантийного срока в товаре обнаружатся недостатки, Вы можете вернуть его продавцу и
                получить назад деньги, заменить товар на такой же без дефектов или на другую продукцию с перерасчетом
                стоимости.
                <br><br>
                Гарантия не действует, если недостатки возникли из-за неправильного хранения и эксплуатации товара или
                воздействия сторонних обстоятельств (стихийных бедствий и т.п.), а также не распространяется на
                расходные материалы, бывшие в употреблении или уцененные товары.
                <br><br>
                <Link href="/warranties"
                      class="text-ui-text-accent border-b border-ui-text-accent border-dashed font-bold">
                    Подробнее о гарантийных обязательствах
                </Link>
            </div>
            <div v-if="activeTab==='shipment'" class="flex flex-col gap-y-6">
                <p>Доставка товаров производится с нашего склада. Доступные варианты:</p>
                <ol class="list-decimal flex flex-col gap-y-6">
                    <li class="pl-6"><b>Доставка курьером по Москве и Московской области.</b> Стоимость - 290 руб. (при
                        заказе от 5000 руб. - бесплатно).
                    </li>
                    <li class="pl-6">
                        <b>Самовывоз.</b> Бесплатный самовывоз из магазинов ДомДом по адресу:
                        <ul class="mt-8 ml-4 list-disc flex flex-col gap-y-6">
                            <li>г. Жуковский, ул. Гагарина, д 67, 3 этаж</li>
                            <li>гор. Люберцы, пгт Томилино, Егорьевское шоссе, стр.2, ТЦ «Торговый Дом Томилино», 2
                                этаж
                            </li>
                            <li>г. Москва, Кронштадтский бульвар, 3а, м. Водный стадион, ТЦ «Гавань», -1 этаж</li>
                        </ul>
                    </li>
                </ol>
                <Link href="/shipment"
                      class="mt-4 w-fit text-ui-text-accent border-b border-ui-text-accent border-dashed font-bold">
                    Подробнее о доставке и самовывозе
                </Link>
            </div>
            <div v-if="activeTab==='payments'" class="flex flex-col gap-y-6">
                <h3 style="font-size: 36px; color: #000000; margin-bottom: 0;">Способы оплаты</h3>
                <p>Вы можете оплатить заказанный в Интернет-магазине товар несколькими способами:</p>
                <ul class="ml-8 list-disc flex flex-col gap-9">
                    <li><b>Наличными</b>
                        <br><br>
                        Оплата производится в рублях при получении заказа. Вместе с заказом будут передан кассовый чек.
                        Частичный выкуп возможен. Вы также можете отказаться от вручения доставленного заказа.
                    </li>
                    <li><b>Банковской картой</b>
                        <br><br>
                        Вы можете оплатить покупку банковской картой Visa, Mastercard, МИР. Для этого при оформлении
                        заказа выберите способ оплаты «Банковской картой».
                    </li>
                    <li><b>Безналичным платежом</b>
                        <br><br>
                        Если вам необходимо выставить счет на оплату вашего заказа юридическому лицу, при оформлении
                        покупки на сайте укажите в примечании тип оплаты «Безналичный платеж». Наш оператор свяжется с
                        вами, согласует все условия по доставке товара, проверит контакты и выставит счет.
                    </li>
                </ul>
            </div>
            <div v-if="activeTab==='replies'" class="flex flex-col gap-y-6">
                <div v-for="reply in [1,2]" class="reply px-4 py-5 bg-ui-light border">
                    <div class="flex"><img v-for="star in [1,1,1,1,1]" src="/images/star.png" alt=""></div>
                    <div>
                        <div class="my-2 font-bold">Николай</div>
                        <div>
                            Хороший аппарат. Все сварочные работы выполняются отлично. Уже три года им пользуюсь, ни
                            разу не понадобился ремонт. Сваривал металл от 1,5 мм до 12 мм разными типами электродов.
                            Нареканий никаких нет. Только кабель коротковат, использую удлинитель.
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<style scoped>

</style>
