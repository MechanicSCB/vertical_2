<script setup>
import {useForm} from "@inertiajs/vue3"
import FormErrors from "../../../Layouts/Partials/FormErrors.vue";

let form = useForm({
    title:'',
    slug:'',
});

function submit(){
    form.post(route('admin.categories.store'));
}
</script>
<script>
import AdminLayout from "../../../Layouts/AdminLayout.vue";

export default {
    layout: AdminLayout,
}
</script>
<template>
    <Head title="Админ-панель: Создать категорию"/>
    <h1>Создать категорию</h1>

    <!--  Errors messages  -->
    <FormErrors class="mb-5" :form="form"/>

    <form  @submit.prevent="submit" class="bg-gray-200 flex flex-col">
        <label>Title<input v-model="form.title" type="text"></label>
        <label>Slug<input v-model="form.slug" type="text"></label>

        <!--      Submit Button      -->
        <button class="w-fit" @click="submit">Submit</button>
    </form>
</template>
