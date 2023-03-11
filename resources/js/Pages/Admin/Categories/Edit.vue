<script setup>
import {useForm} from "@inertiajs/vue3"
import FormErrors from "../../../Layouts/Partials/FormErrors.vue";

let props = defineProps({category:Object});

let form = useForm({
    title:props.category.title,
    slug:props.category.slug,
});

function submit(){
    form.patch(route('admin.categories.update',props.category));
}
</script>
<script>
import AdminLayout from "../../../Layouts/AdminLayout.vue";

export default {
    layout: AdminLayout,
}
</script>
<template>
    <Head :title="'Админ-панель: Редактировать категорию ' + category.title"/>
    <h1>Редактировать категорию: {{ category.title }}</h1>

    <div>Updated At {{ category.updated_at }}</div>

    <!--  Errors messages  -->
    <FormErrors class="mb-5" :form="form"/>

    <form  @submit.prevent="submit" class="bg-gray-200 flex flex-col">
        <label>Title<input v-model="form.title" type="text"></label>
        <label>Slug<input v-model="form.slug" type="text"></label>

        <!--      Submit Button      -->
        <button class="w-fit" @click="submit">Submit</button>
    </form>
</template>
