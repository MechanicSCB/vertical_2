<script setup>
import {useForm, router} from "@inertiajs/vue3";
import {onMounted, reactive, ref} from 'vue'
import ProductCard from "./ProductCard.vue";
import FilterIcon from "../../../Svg/FilterIcon.vue";
import Multiselect from "./Multiselect.vue";
import noUiSlider from 'nouislider';
import 'nouislider/dist/nouislider.css';

let props = defineProps({
    filterData: Object,
    productsTotal: Number,
});

let form = useForm({
    sortBy: props.filterData.form.sortBy ?? 'popular',
    priceFrom: props.filterData.form.priceFrom ?? '',
    priceTo: props.filterData.form.priceTo ?? '',
    params: props.filterData.form.params ?? {},
});

let slider = reactive({});

onMounted(() => {
    slider = document.getElementById('slider');

    noUiSlider.create(slider, {
        start: [props.filterData.minPrice, props.filterData.maxPrice],
        step: 1,
        connect: true,
        range: {'min':  props.filterData.minPrice, 'max':  props.filterData.maxPrice},
    });

    slider.noUiSlider.on('update', function (values, handle) {
        form.priceFrom = parseInt(values[0]);
        form.priceTo = parseInt(values[1]);
    });

    slider.noUiSlider.on('set', function (values, handle) {
        submit();
    });
});

function changePriceInput(){
    slider.noUiSlider.set([(form.priceFrom + '').replace(/\D/g,''), (form.priceTo + '').replace(/\D/g,'')]);
}

function submit() {
    // let url = router.page.url.split('?')[0];
    let url = router.page.props.ziggy.location;

    form.get(url, {
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
            <div class="ml-8 flex items-center text-sm">Товаров: <span class="ml-1">{{ productsTotal }}</span></div>
        </div>

        <!-- FORM -->
        <div class="px-8 py-4 border">
            <!--  SORT  -->
            <select @change="submit" v-model="form.sortBy"
                    class="mt-2 w-full px-6 py-3 text-lg border border-ui-border-primary rounded-[30px]" type="text">
                <option v-for="(option,key) in filterData.sort_options" :value="key">{{ option.name }}</option>
            </select>

            <!-- PRICE FILTER -->
            <div class="mt-6">
                <div class="text-lg">Цена</div>
                <!-- noUiSlider   https://refreshless.com/nouislider/ -->
                <div id="slider" class="my-9 h-1 bg-ui-accent"></div>
                <div class="flex gap-8">
                    <input @change="changePriceInput" v-model="form.priceFrom" :placeholder="filterData.minPrice"
                           class="w-full px-6 py-3 text-lg border border-ui-border-primary rounded-[30px]" type="text"  pattern="\d*">
                    <input @change="changePriceInput" v-model="form.priceTo" :placeholder="filterData.maxPrice"
                           class="w-full px-6 py-3 text-lg border border-ui-border-primary rounded-[30px]" type="text">
                </div>
            </div>

            <!-- Custom Multiselects  -->
            <Multiselect @submit="submit" v-for="(values,param) in filterData.params" :name="param" :form="form"
                         :options="values" :field="param"/>

            <!--  Buttons  -->
            <div class="mt-6 flex flex-col gap-3">
                <button @click.prevent="submit"
                        class="py-3 bg-ui-accent text-ui-text-accent_inverse hover:bg-ui-body hover:text-ui-text-accent border-[3px] border-ui-text-accent text-lg font-semibold rounded-[30px]">
                    Применить
                </button>
                <!-- TODO ref reset button behaviour -->
                <Link :href="$page.props.ziggy.location"
                      class="text-center py-3 bg-ui-body text-ui-text-accent hover:bg-ui-accent hover:text-ui-text-accent_inverse border-[3px] border-ui-text-accent text-lg font-semibold rounded-[30px]">
                    Сбросить
                </Link>
            </div>

            <!-- productsTotal -->
            <div class="mt-4 product-count">Найдено товаров: <span class="js-product-count">{{ productsTotal }}</span></div>
        </div>
    </div>
</template>
<style>
#slider {
    border: 0;
    height: 4px;
}
.noUi-horizontal {
    /*height: 100%;*/
}

.noUi-connects {
    background: var(--color-ui-text-light);
}

.noUi-connect {
    background: var(--color-ui-text-accent);
}

.noUi-horizontal .noUi-handle {
    width: 20px;
    height: 20px;
    right: -8px;
    top: -8px;
    border: 0;
    box-shadow: none;
    border-radius: 10px;
    background: var(--color-ui-text-accent);
    cursor: pointer;
}

.noUi-active {
    /*box-shadow: none;*/
}

.noUi-handle:before,
.noUi-handle:after {
    display: none;
}

</style>
