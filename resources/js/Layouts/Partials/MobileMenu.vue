<script setup>
import CatalogIcon from "../../Svg/CatalogIcon.vue";
import GeolocationIcon from "../../Svg/GeolocationIcon.vue";
import MobileMenuItem from "./MobileMenuItem.vue";
import {inject} from "vue";

let showMobileMenu = inject('showMobileMenu');
</script>

<template>
    <div @click="showMobileMenu=false" class="fixed xl:hidden z-10 w-full h-screen bg-[rgba(0,0,0,0.4)]">
        <div @click.stop class="md:w-5/12 w-4/5 h-full text-ui-text-primary font-semibold bg-ui-secondary overflow-y-auto pb-14">
            <!-- TODO remove dashed border and add active link border -->
            <div class="flex flex-col text-lg">
                <MobileMenuItem href="">
                    <template #icon>
                        <GeolocationIcon class="w-7 fill-ui-text-secondary"/>
                    </template>
                    Москва
                </MobileMenuItem>
                <MobileMenuItem href="/catalog">
                    <template #icon>
                        <CatalogIcon class="w-6 fill-ui-text-accent"/>
                    </template>
                    Каталог
                </MobileMenuItem>
                <MobileMenuItem href="/sets">
                    Акции
                </MobileMenuItem>
                <MobileMenuItem href="/opt">
                    Оптовым клиентам
                </MobileMenuItem>
                <MobileMenuItem href="/shipment">
                    Доставка
                </MobileMenuItem>
                <MobileMenuItem href="/return">
                    Возврат
                </MobileMenuItem>
                <MobileMenuItem href="/vacancy">
                    Работа в компании
                </MobileMenuItem>
            </div>
        </div>
    </div>
</template>
