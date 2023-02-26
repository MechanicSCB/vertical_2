<script setup>
import { useForm } from '@inertiajs/vue3';
import {router} from "@inertiajs/vue3";
import ProductCard from "./ProductCard.vue";
import FilterIcon from "../../../Svg/FilterIcon.vue";
import ExpandIcon from "../../../Svg/ExpandIcon.vue";
import CollapseIcon from "../../../Svg/CollapseIcon.vue";

let props = defineProps({
    filterData:Object,
    products:Object,
});

let form = useForm({
    priceFrom:null,
    priceTo:'',
});

function submit() {
    form.get('#', {
        preserveScroll: true,
        preserveState: true,
    })
}

</script>

<template>
    <div class="">
        <div class="flex w-full">
            <div class="-mb-[1px] flex items-end px-8 py-4 rounded-t-[30px] border border-b-white text-2xl font-semibold text-ui-text-accent">
                <span class="mb-0.5">Фильтр</span>
                <FilterIcon class="ml-1 fill-ui-text-accent"/>
            </div>
            <div class="ml-8 flex items-center text-sm">Товаров: <span class="ml-1">{{products.total}}</span></div>
        </div>
        <form class="px-8 py-4 border rounded-[30px] rounded-tl-none">
            <div class="">
                <div class="text-lg">Цена</div>
                <!-- https://refreshless.com/nouislider/ -->
                <div class="my-9 h-1 bg-ui-accent"></div>
                <div class="flex gap-8">
                     <input @change="submit" v-model="form.priceFrom" :placeholder="filterData.minPrice" class="w-full px-6 py-3 text-lg border border-ui-border-primary rounded-[30px]" type="text">
                    <input @change="submit" v-model="form.priceTo" :placeholder="filterData.maxPrice" class="w-full px-6 py-3 text-lg border border-ui-border-primary rounded-[30px]" type="text">
                </div>
            </div>

            <!-- Checkboxes List  -->
            <div class="text-lg">
                <button @click.prevent="" class="my-2 flex items-center gap-4">
                    <span class="py-1 border-b border-dashed border-ui-text-accent">Бренд</span>
                    <ExpandIcon class="w-6"/>
                    <CollapseIcon class="w-6 hidden"/>
                </button>
                <div class="overflow-y-auto max-h-[250px] flex flex-col gap-3.5">
                    <label v-for="vendor in filterData.vendors" class="flex items-center gap-4">
                        <input type="checkbox" class="hidden">
                        <span class="w-5 h-5 border border-ui-text-secondary rounded"></span>
                        <span>{{ vendor }}</span>
                    </label>
                </div>
            </div>

            <div class="mt-4 flex flex-col gap-3">
                <button @click.prevent="submit" class="py-3 bg-ui-accent text-ui-text-accent_inverse hover:bg-ui-body hover:text-ui-text-accent border-[3px] border-ui-text-accent text-lg font-semibold rounded-[30px]">
                    Применить
                </button>
                <Link :href="$page.props.ziggy.location" class="text-center py-3 bg-ui-body text-ui-text-accent hover:bg-ui-accent hover:text-ui-text-accent_inverse border-[3px] border-ui-text-accent text-lg font-semibold rounded-[30px]">
                    Сбросить
                </Link>
                <div class="bx-filter-popup-result " id="modef" style="display:none;"> Выбрано: <span id="modef_num">0</span> <span class="arrow"></span>
                    <br>
                    <a href="/catalog/lakokrasochnye-materialy/gruntovki/gruntovki-akrilovye/filter/clear/" target="_self">Показать</a>
                </div>
            </div>
            <div class="mt-4 product-count">Найдено товаров: <span class="js-product-count">55</span></div>
        </form>
    </div>
</template>
