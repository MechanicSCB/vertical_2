<script setup>
import Breadcrumbs from "../../Layouts/Partials/Breadcrumbs.vue";
import HeartIcon from "../../Svg/HeartIcon.vue";
import {ref} from "vue";
import CheckedIcon from "../../Svg/CheckedIcon.vue";
import CursorArrowClickedIcon from "../../Svg/CursorArrowClickedIcon.vue";
import ProductTabs from "./Partials/ProductTabs.vue";

let showModalImg = ref(false);
let showOrigSizeImg = ref(false);

let props = defineProps({
    breadcrumbs: Object,
    product: Object,
});
</script>
<template>
    <Breadcrumbs :breadcrumbs="breadcrumbs"/>

    <!--    MODAL IMAGE    -->
    <div @click="showModalImg=!showModalImg" v-if="showModalImg"
         class="top-0 p-12 flex items-center min-h-screen fixed overflow-auto bg-[rgba(0,0,0,0.8)] w-full h-full z-50">
        <img @click.stop="showOrigSizeImg=!showOrigSizeImg" :src="'/storage/images/products/orig/'+product.code+'.jpg'"
             :class="showOrigSizeImg ? 'cursor-zoom-out max-w-fit':'max-h-full cursor-zoom-in'"
             class="mx-auto transition duration-300 ease-out"
        >
    </div>

    <div class="mt-6 mx-auto px-9 max-w-[1656px]">
        <!--  IMAGE/BUY CARD  -->
        <div class="mt-10 flex md:flex-row flex-col gap-8">
            <!--  LEFT  -->
            <div class="xl:w-1/3 md:w-1/2 w-full">
                <!-- IMAGE CARD -->
                <div class="relative border rounded-3xl overflow-hidden h-[510px] flex items-center">
                    <!-- FAVORITE -->
                    <HeartIcon
                        class="absolute right-5 top-4 fill-ui-text-light hover:fill-ui-link-hover w-10 h-10 hover:w-[42px] hover:[42px] cursor-pointer"/>

                    <!-- IMAGE -->
                    <div class="px-2 product-link max-h-full flex flex-col">
                        <img @click="showModalImg = true" class="mx-auto h-full cursor-pointer"
                             :src="'/storage/images/products/orig/'+product.code+'.jpg'">
                    </div>
                </div>
            </div>

            <!--  RIGHT  -->
            <div class="xl:w-2/3 md:w-1/2 w-full">
                <h1 class="text-5xl font-semibold" :title="product.name">{{ product.name }}</h1>

                <!-- CODE/RATING -->
                <div class="mt-3 flex flex-col text-ui-text-secondary">
                    <div>Код товара: {{ product.code }}</div>

                    <div class="mt-3 flex gap-2">
                        <div class="flex"><img v-for="star in [1,1,1,1,1]" src="/images/star.png" alt=""></div>
                        <span class="review-label">{{ product.reply_count }} отзыв </span>
                    </div>
                </div>

                <!-- BUY CARD -->
                <div class="mt-10 px-6 py-6 rounded-3xl bg-ui-light text-ui-text-secondary">
                    <!--  availability  -->
                    <div class="flex items-center text-ui-text-accent gap-6">
                        <CheckedIcon class="w-4 fill-ui-text-accent"/>
                        {{ product.availability !== '' ? 'В наличии на складе' : 'Товара нет в наличии' }}
                    </div>

                    <!-- PRICE/BUY BLOCK -->
                    <div class="mt-10 flex flex-wrap gap-x-4">
                        <!-- PRICE -->
                        <h2 class="text-[40px] font-semibold text-ui-text-primary">{{ product.price.toLocaleString() }} ₽</h2>

                        <!-- ADD TO CART -->
                        <div class="mt-4 flex gap-x-3">
                            <!-- quantity/availability -->
                            <div>
                                <!-- quantity -->
                                <div class="px-5 flex border bg-ui-body w-fit h-[60px] rounded-full items-center justify-between text-3xl">
                                    <button @click="product.quantity--" class="mb-3 text-[40px] mr-1 text-ui-text-primary hover:text-ui-link-hover">-</button>
                                    <div class="mx-5 text-center">{{ product.quantity ??= 1 }}</div>
                                    <button @click="product.quantity++" class="mb-2 text-[40px] text-ui-text-primary hover:text-ui-link-hover">+</button>
                                </div>
                                <div class="mt-2 -mb-1">В наличии: <strong class="font-semibold text-ui-text-accent">{{ product.availability }}</strong></div>
                                <div v-if="product.quantity > 1"><span>На сумму </span><span class="font-semibold">{{ (product.price * product.quantity).toLocaleString() }} ₽</span></div>
                            </div>

                            <!-- add to cart -->
                            <button class="add-to-cart h-[60px] w-32 bg-ui-accent hover:bg-ui-body text-ui-text-accent_inverse hover:text-ui-text-accent border-[3px] border-ui-text-accent text-sm font-semibold rounded-[30px]">
                                В корзину
                            </button>
                        </div>
                    </div>

                    <!-- OPT LINK -->
                    <Link href="#" class="mt-6 flex text-center" preserve-scroll>специальные&nbsp;цены для&nbsp;юридических&nbsp;лиц</Link>
                </div>

                <!--  FIND IT CHEAPER/BUY IN ONE CLICK -->
                <div class="mt-10 mx-auto flex gap-6 justify-center">
                    <button class="flex hover:text-ui-link-hover hover:fill-ui-link-hover text-ui-text-secondary fill-ui-text-secondary">
                        Нашли дешевле?
                    </button>
                    <button class="flex hover:text-ui-link-hover hover:fill-ui-link-hover text-ui-text-secondary fill-ui-text-secondary">
                        <CursorArrowClickedIcon class="w-5"/>
                        Купить в один клик
                    </button>
                </div>

            </div>
        </div>

        <!-- PRODUCT TABS  -->
        <ProductTabs :product="product"/>

        <!-- ADDDDD -->
        <div class="hidden tab-wrapper">
            <div class="tab-header">
                <a href="javascript:void(0)" data-target="#tab-reviews" class="js-att-tab"><span>Отзывы</span></a>
            </div>
            <div class="tab" id="tab-reviews">
                <div class="reviews">
                    <div class="reviews-head todo">
                        <div class="grid-row">
                            <div class="col col-xl-2 col-lg-2 col-md-3 col-sm-12">
                                <div class="reviews-head-title">Отзывов: 75</div>
                            </div>
                            <div class="col col-xl-10 col-lg-10 col-md-9 col-sm-12">
                                <div class="reviews-head-info">
                                    <div class="sorter">
                                        <span class="sorter-title">Сортировать:</span>
                                        <a href="javascript:void(0)" class="active asc"><span>по новизне</span></a>
                                        <a href="javascript:void(0)"><span>по оценке</span></a>
                                        <a href="javascript:void(0)"><span>по пользе</span></a>
                                    </div>
                                    <select class="sorter">
                                        <option value="" selected="" disabled="">Сортировать по:</option>
                                        <option value="1">по новизне по возрастанию</option>
                                        <option value="2">по новизне по убыванию</option>
                                        <option value="3">по оценке по возрастанию</option>
                                        <option value="4">по оценке по убыванию</option>
                                        <option value="5">по пользе по возрастанию</option>
                                        <option value="6">по пользе по убыванию</option>
                                    </select>
                                    <a href="javascript:void(0)" class="btn fill-lipstick"><span>Оставить отзыв</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="reviews-list">
                    </div>
                    <div class="wdr2_list" id="wdr2_list_jRlUXc">
                        <div class="wdr2_items">
                            <div id="wdr2_item_273">
                                <div class="wdr2_item" id="bx_3566614279_273">
                                    <a name="273"></a>
                                    <div class="wdr2_baloon_body">
                                        <div class="wdr2_item_rating">
                                            <div id="wd_reviews2_rating_b4796f07e8803bbf44231e1c74efd1da" class="wd_reviews2_rating" style="white-space:nowrap" title=""><img alt="1" src="/upload/webdebug.reviews/1/star/active.png" title=""><img alt="2" src="/upload/webdebug.reviews/1/star/active.png" title=""><img alt="3" src="/upload/webdebug.reviews/1/star/active.png" title=""><img alt="4" src="/upload/webdebug.reviews/1/star/active.png" title=""><img alt="5" src="/upload/webdebug.reviews/1/star/active.png" title=""><input name="score" type="hidden" value="5" readonly=""></div>	<div class="wdr2_item_rating_detail">
                                            <div class="wdr2_one_rating">
                                                Рейтинг<br>
                                                <div id="wd_reviews2_rating_1659cddcc2f72a6dd692665a53eac680" class="wd_reviews2_rating" style="white-space:nowrap" title=""><img alt="1" src="/upload/webdebug.reviews/1/star/active.png" title=""><img alt="2" src="/upload/webdebug.reviews/1/star/active.png" title=""><img alt="3" src="/upload/webdebug.reviews/1/star/active.png" title=""><img alt="4" src="/upload/webdebug.reviews/1/star/active.png" title=""><img alt="5" src="/upload/webdebug.reviews/1/star/active.png" title=""><input name="score" type="hidden" value="5" readonly=""></div>	</div>
                                        </div>
                                        </div>
                                        <div class="wdr2_fields">
                                            <div class="wdr2_field"><div class="wdr2_field_value" style="font-weight: bold;">Николай</div></div>
                                            <div class="wdr2_field"><div class="wdr2_field_value">Хороший аппарат. Все сварочные работы выполняются отлично. Уже три года им пользуюсь, ни разу не понадобился ремонт. Сваривал металл от 1,5 мм до 12 мм разными типами электродов. Нареканий никаких нет. Только кабель коротковат, использую удлинитель. </div></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="wdr2_pager wdr2_pager_bottom"></div>
                    </div>
                    <br>
                    <div class="wdr2_iframe_hidden" style="display:none"><iframe onload="wdr2_add_iframe_1_loaded(this);" src="about:blank" name="wdr2_add_iframe_1" id="wdr2_add_iframe_1" class="wdr2_iframe"></iframe></div>
                    <div class="wdr2_add wdr2_add_1">
                        <div class="base-form ready wdr2_form_wrapper">
                            <div class="form_title">Оставить отзыв</div>
                            <div class="wdr2_result"></div>
                            <form name="wdr2_add_form_1" id="wdr2_add_form_1" target="wdr2_add_iframe_1" action="/bitrix/tools/wd_reviews2.php?action=save&amp;interface=1&amp;target=E_451855&amp;anticache=751559884" enctype="multipart/form-data" method="post">
                                <div>
                                    <div class="ratings">
                                        <table>
                                            <tbody>
                                            <tr>
                                                <td class="rating_title">Рейтинг:</td>
                                                <td class="rating_value"><div id="wd_reviews2_rating_df8b9ba2cf410436c014f43c9e5f099f" class="wd_reviews2_rating" style="white-space: nowrap; cursor: pointer;"><img alt="1" src="/upload/webdebug.reviews/1/star/inactive.png" title=""><img alt="2" src="/upload/webdebug.reviews/1/star/inactive.png" title=""><img alt="3" src="/upload/webdebug.reviews/1/star/inactive.png" title=""><img alt="4" src="/upload/webdebug.reviews/1/star/inactive.png" title=""><img alt="5" src="/upload/webdebug.reviews/1/star/inactive.png" title=""><input name="review[RATINGS][1]" type="hidden"></div></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="fields">
                                        <div class="field_name">
                                            <label>
                                                <input type="text" name="review[FIELDS][NAME]" value="" id="wdr2_ujnvsq">
                                                <span class="placeholder">Имя</span>
                                            </label>
                                        </div>
                                        <div class="field_comment">
                                            <label class="textarea-row">
                                                <textarea name="review[FIELDS][COMMENT]" style="overflow:auto; resize:vertical; " class="textarea-row" id="wdr2_notlz6"></textarea>
                                                <span class="placeholder">Комментарий</span>
                                            </label>
                                        </div>
                                        <div class="field wdr2_reqfield"><input type="text" name="field_1" value=""></div>
                                    </div>
                                    <div class="submit">
                                        <input class="btn fill-lipstick" type="submit" value="Отправить">
                                    </div>
                                    <input type="hidden" name="wdr2_interface" value="1">
                                    <input type="hidden" name="wdr2_target" value="E_451855">
                                    <input type="hidden" name="wdr2_form" value="1">
                                    <input type="hidden" name="wdr2_field" value="review">
                                    <input type="hidden" name="wdr2_site" value="s1">
                                    <input type="hidden" name="wdr2_url" value="%2Fproduct%2Fsvarochnyy-apparat-resanta-saipa-165-mig-mag-poluavtomat-6200-vt%2F">
                                    <input type="hidden" name="wd_reviews2_review_sessid" id="wd_reviews2_review_sessid" value="254f8cfd87014e13e6e6b93ab2441930">	</div>
                            </form>
                        </div>
                    </div>
                    <br><br>
                </div>
            </div>
        </div>
    </div>
</template>
