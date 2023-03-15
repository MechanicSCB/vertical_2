<script setup>
import HeartIcon from "../../../Svg/HeartIcon.vue";
import ExpandIcon from "../../../Svg/ExpandIcon.vue";
import CollapseIcon from "../../../Svg/CollapseIcon.vue";
import {ref} from "vue";

defineProps({product:Object});
let showModalImg = ref(false);
let showOrigSizeImg = ref(false);
const headSlide = ref(null)
const activeSlide = ref(null)

function slideUp(){
    console.log(activeSlide);

}
</script>
<template>
    <!--    MODAL IMAGE    -->
    <div @click="showModalImg=!showModalImg" v-if="showModalImg"
         class="top-0 p-12 flex items-center min-h-screen fixed overflow-auto bg-[rgba(0,0,0,0.8)] w-full h-full z-50">
        <img @click.stop="showOrigSizeImg=!showOrigSizeImg" :src="'/storage/images/products/cropped/'+product.code+'.jpg'"
             :class="showOrigSizeImg ? 'cursor-zoom-out max-w-fit':'max-h-full cursor-zoom-in'"
             class="mx-auto border-8 border-white transition duration-300 ease-out"
        >
    </div>

    <div class="flex gap-9">
        <!-- IMAGE CARD -->
        <div class="relative border rounded-3xl overflow-hidden h-[510px] flex items-center">
            <!-- FAVORITE -->
            <HeartIcon class="absolute right-5 top-4 fill-ui-text-light hover:fill-ui-link-hover w-10 h-10 hover:w-[42px] hover:[42px] cursor-pointer"/>

            <!-- IMAGE -->
            <div class="p-2 mx-auto product-link max-h-full flex flex-col">
                <img @click="showModalImg = true" class="mx-auto h-full cursor-pointer"
                     :src="'/storage/images/products/cropped/'+product.code+'.jpg'">
            </div>
        </div>
        <div class="flex flex-col h-[510px] justify-between gap-4">
            <button @click="slideUp" class="w-full h-12 group">
                <ExpandIcon class="mx-auto group-hover:mb-2 w-11 fill-ui-text-accent transition-all"/>
            </button>
            <div class="h-[400px] flex-col overflow-hidden">
                <div class="-mt-[128px]">
                    <div class="mt-8 w-[100px] h-[100px] overflow-hidden">
                        <img @click="" class="mx-auto h-full cursor-pointer"
                             :src="'/storage/images/products/1/'+product.code+'_3.jpg'">
                    </div>
                    <div class="mt-8 w-[100px] h-[100px] overflow-hidden">
                        <img @click="" class="mx-auto h-full cursor-pointer"
                             :src="'/storage/images/products/1/'+product.code+'_1.jpg'">
                    </div>
                    <div ref="activeSlide" class="mt-8 w-[100px] h-[100px] rounded-xl border border-ui-text-accent overflow-hidden">
                        <img @click="" class="mx-auto h-full cursor-pointer"
                             :src="'/storage/images/products/1/'+product.code+'_2.jpg'">
                    </div>
                    <div class="mt-8 w-[100px] h-[100px]">
                        <img @click="" class="mx-auto h-full cursor-pointer"
                             :src="'/storage/images/products/1/'+product.code+'_3.jpg'">
                    </div>
                    <div class="mt-8 w-[100px] h-[100px]">
                        <img @click="" class="mx-auto h-full cursor-pointer"
                             :src="'/storage/images/products/1/'+product.code+'_1.jpg'">
                    </div>
                </div>
            </div>
            <button class="w-full h-12 group">
                <CollapseIcon class="mx-auto group-hover:mt-2 w-11 fill-ui-text-accent transition-all"/>
            </button>
        </div>
    </div>
</template>
