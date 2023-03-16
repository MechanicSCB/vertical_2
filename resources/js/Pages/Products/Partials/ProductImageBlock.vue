<script setup>
import HeartIcon from "../../../Svg/HeartIcon.vue";
import ExpandIcon from "../../../Svg/ExpandIcon.vue";
import CollapseIcon from "../../../Svg/CollapseIcon.vue";
import {reactive, ref} from "vue";
import CloseIcon from "../../../Svg/CloseIcon.vue";
import MagnifyIcon from "../../../Svg/MagnifyIcon.vue";

let props = defineProps({product: Object});
let showModalImg = ref(false);
let showOrigSizeImg = ref(false);
let slidePreviews = ref([...props.product.previews, ...props.product.previews]);

function slideUp() {
    slidePreviews.value.unshift(slidePreviews.value.pop());
}

function slideDown() {
    slidePreviews.value.push(slidePreviews.value.shift());
}

function getActiveImageLink() {
    return slidePreviews.value[slidePreviews.value.length/2].replace('s220', 'cropped');
}
</script>
<template>
    <!--    MODAL IMAGE    -->
    <div @click="showModalImg=!showModalImg" v-if="showModalImg"
         class="top-0 left-0 p-10 flex min-h-screen fixed overflow-auto bg-[rgba(0,0,0,0.8)] w-full h-full z-50"
         :class="showOrigSizeImg?'items-start':'items-center'"
    >
        <button class="absolute top-0 right-10 w-10 h-10 bg-black opacity-50 hover:opacity-75">
            <CloseIcon class="mx-auto fill-white w-6"/>
        </button>
        <button @click.stop="showOrigSizeImg=!showOrigSizeImg" class="absolute top-0 right-0 w-10 h-10 bg-black opacity-50 hover:opacity-75">
            <MagnifyIcon class="mx-auto fill-white w-5"/>
        </button>
        <button v-show="slidePreviews.length > 2" @click.stop="slideUp" class="absolute top-1/2 left-2 w-10 h-10 bg-black opacity-50 hover:opacity-75">
            <svg class="mx-auto fill-white w-6"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M11.28 15.7l-1.34 1.37L5 12l4.94-5.07 1.34 1.38-2.68 2.72H19v1.94H8.6z"></path></svg>
        </button>

        <img @click.stop="showOrigSizeImg=!showOrigSizeImg"
             :src="getActiveImageLink()"
             :class="showOrigSizeImg ? 'cursor-zoom-out max-w-fit':'max-h-full cursor-zoom-in'"
             class="mx-auto border-[10px] border-white transition duration-300 ease-out"
        >

        <button v-show="slidePreviews.length > 2" @click.stop="slideDown" class="absolute top-1/2 right-2 w-10 h-10 bg-black opacity-50 hover:opacity-75">
            <svg class="mx-auto fill-white w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M15.4 12.97l-2.68 2.72 1.34 1.38L19 12l-4.94-5.07-1.34 1.38 2.68 2.72H5v1.94z"></path></svg>
        </button>
    </div>

    <div class="flex gap-9">
        <!-- IMAGE CARD -->
        <div class="relative w-full border rounded-3xl overflow-hidden h-[510px] justify-between flex items-center">
            <!-- FAVORITE -->
            <HeartIcon class="absolute right-5 top-4 fill-ui-text-light hover:fill-ui-link-hover w-10 h-10 hover:w-[42px] hover:[42px] cursor-pointer transition-all"/>

            <!-- IMAGE -->
            <div class="p-2 mx-auto product-link h-full flex items-center">
                <div class="max-w-full h-full flex items-center">
                    <img @click="showModalImg = true" class="border-[10px] border-white mx-auto max-h-full cursor-pointer"
                         :src="getActiveImageLink()">
                </div>
            </div>
        </div>
        <div v-if="slidePreviews.length > 2" class="flex flex-col h-[510px] justify-between gap-4">
            <button @click="slideUp" class="w-full h-12 group">
                <ExpandIcon class="mx-auto group-hover:mb-2 w-11 fill-ui-text-accent transition-all"/>
            </button>
            <!-- slides window  -->
            <div class="h-[390px] overflow-hidden flex items-center">
                <!-- slides list  -->
                <div class="mb-[130px] transition-all">
                    <div v-for="(link, key) in slidePreviews" class="w-[100px] h-[130px] overflow-hidden">
                        <div @click="key<(slidePreviews.length/2) ? slideUp() : slideDown() "
                             class="mt-[15px] w-[100px] h-[100px] flex items-center rounded-xl overflow-hidden hover:border border-ui-text-accent"
                             :class="key===(slidePreviews.length/2)?'border':''">
                            <img class="mx-auto max-h-full cursor-pointer" :src="link">
                        </div>
                    </div>
                </div>
            </div>
            <button @click="slideDown" class="w-full h-12 group">
                <CollapseIcon class="mx-auto group-hover:mt-2 w-11 fill-ui-text-accent transition-all"/>
            </button>
        </div>
    </div>
</template>
