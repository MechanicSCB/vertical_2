<script setup>
import TelegramLogo from "../../Svg/TelegramLogo.vue";
import WhatsupLogo from "../../Svg/WhatsupLogo.vue";

</script>
<template>
    <div class="text-ui-text-secondary">
        <div class="mt-12 mx-auto max-w-[1656px] xl:px-9 px-28 bg-ui-section">
            <div class="grid xl:grid-cols-4 grid-cols-2 gap-10">
                <!-- CONTACTS -->
                <div class="sm:flex flex-col hidden gap-6">
                    <h3 class="text-2xl font-semibold text-ui-text-primary">Контакты</h3>
                    <div class="flex flex-col">
                        <div class="flex flex-col">
                            <span class="text-sm">9:00 — 21:00, ежедневно</span>
                            <div class="text-lg font-semibold">8 (499) 110-71-74</div>
                            <Link class="text-sm border-b border-dashed w-fit hover:text-ui-link-hover" href="#callbackwidget">заказать звонок</Link>
                            <div>contact@vertical.ru</div>
                            <Link href="#" class="hover:text-ui-link-hover w-fit">Обратная связь</Link>
                        </div>
                        <div class="mt-2 flex gap-1">
                            <a href="#" target="_blank"><TelegramLogo class="w-10 h-10"/></a>
                            <a href="#" target="_blank"><WhatsupLogo class="w-10 h-10" /></a>
                        </div>
                    </div>
                    <a href="https://market.yandex.ru" target="_blank">
                        <img src="/images/yandex_market.png" alt="Читайте отзывы покупателей и оценивайте качество магазина на Яндекс.Маркете" border="0">
                    </a>
                </div>
                <!-- ABOUT MENU -->
                <div class="sm:flex flex-col hidden gap-6">
                    <h3 class="text-2xl font-semibold text-ui-text-primary">О компании</h3>
                    <ul class="flex flex-col text-sm">
                        <li><Link href="/vacancy">Работа в компании</Link></li>
                        <li><Link href="/about">О нас</Link></li>
                        <li><Link href="/contacts">Контакты</Link></li>
                        <li><Link href="/warranty">Гарантия</Link></li>
                        <li><Link href="/public_offer">Публичная оферта</Link></li>
                        <li><Link href="/privacy">Политика обработки персональных данных</Link></li>
                        <li><Link href="/reviews">Отзывы</Link></li>
                    </ul>
                </div>
                <!-- FOR CUSTOMERS -->
                <div class="sm:flex flex-col hidden gap-6">
                    <h3 class="text-2xl font-semibold text-ui-text-primary">Покупателям</h3>
                    <ul class="flex flex-col text-sm">
                        <li><Link href="/catalog">Каталог</Link></li>
                        <li><Link href="/sets">Акции</Link></li>
                        <li><Link href="/payments">Способы оплаты</Link></li>
                        <li><Link href="/purchase_returns">Возврат товара</Link></li>
                        <li><Link href="/opt">Юридическим лицам</Link></li>
                    </ul>
                </div>
                <!-- Services -->
                <div class="sm:flex flex-col hidden gap-6">
                    <h3 class="text-2xl font-semibold text-ui-text-primary">Услуги</h3>
                    <ul class="flex flex-col text-sm">
                        <li><Link href="/delivery">Доставка</Link></li>
                        <li><Link href="/tinting">Колеровка краски</Link></li>
                    </ul>
                </div>
            </div>
            <div class="mt-24 flex flex-col md:flex-row justify-between">
                <div class="md:w-1/2 w-full md:text-left text-center">©2023 «Вертикаль» </div>
                <div class="md:mt-0 mt-12 flex md:gap-4 gap-8 justify-center">
                    <div class="text-xs">Принимаем к оплате:</div>
                    <div><img src="/images/visa_logo.webp"></div>
                    <div><img src="/images/mastercard_logo.webp"></div>
                    <div><img src="/images/mir_logo.webp"></div>
                </div>
            </div>
        </div>
        <div class="my-5 md:hidden block w-full text-center">
            8 (499) 110-71-74
        </div>
        <div class="mt-16 py-8 px-6 md:block hidden text-center bg-ui-light text-xs">
            Продолжая использование сайта, вы подтверждаете свое согласие на использование файлов cookie и обработку персональных данных.
            <a class="underline hover:text-ui-link-hover" target="_blank" href="/privacy">Подробнее</a>
        </div>
    </div>
</template>

