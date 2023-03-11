<script setup>
import {useForm} from "@inertiajs/vue3"
import FormErrors from "../../../Layouts/Partials/FormErrors.vue";

let props = defineProps({product:Object});

let form = useForm({
    code:props.product.code,
    name:props.product.name,
    slug:props.product.slug,
});

function submit(){
    form.patch(route('admin.products.update',props.product.slug));
}
</script>
<script>
import AdminLayout from "../../../Layouts/AdminLayout.vue";

export default {
    layout: AdminLayout,
}
</script>
<template>
    <Head :title="'Админ-панель: Редактировать категорию ' + product.name"/>
    <h1>Редактировать товар: {{ product.name }}</h1>

    <div>Updated At {{ product.updated_at }}</div>

    <!--  Errors messages  -->
    <FormErrors class="mb-5" :form="form"/>

    <form  @submit.prevent="submit" class="bg-gray-200 flex flex-col">
        <label>Code<input v-model="form.code" type="text"></label>
        <label>Name<input v-model="form.name" type="text"></label>
        <label>Slug<input v-model="form.slug" type="text"></label>

        <!--      Submit Button      -->
        <button class="w-fit" @click="submit">Submit</button>
    </form>
</template>
