<script setup>
import {inject} from "vue";

let props = defineProps({products:Object});

let showSearchArea = inject('showSearchArea');
</script>
<template>
    <!-- Products -->
    <div class="my-2">Товаров найдено {{ products?.total ?? '--' }}</div>
    <div class="max-h-72 overflow-y-auto">
        <div v-for="product in products?.items ?? []">
            <Link @click="showSearchArea=false" :href="'/products/'+product.slug" class="flex items-center mb-3">
                <!-- Image -->
                <div class="w-14 h-20 flex items-center justify-center">
                    <img class="max-h-full" :src="'/storage/images/products/s220/'+product.code+'.jpg'" alt="">
                </div>
                <div class="ml-5 flex flex-col gap-y-1">
                    <div class="font-semibold">{{ product.name }}</div>
                    <div class="text-xs text-ui-text-secondary">Артикул: {{ product.code }}</div>
                    <div class="px-1 rounded bg-gray-400 text-xxs text-ui-text-accent_inverse w-fit">В наличии</div>
                    <div class="text-base font-semibold">{{ product.price.toLocaleString() }} ₽</div>
                </div>
            </Link>
        </div>
    </div>
</template>
