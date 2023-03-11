<script setup>
import {useForm} from "@inertiajs/vue3"

let form = useForm({
    title:'Новая категория',
    // parent_nodes:[{'id':618,'order_inside':5},{'id':2931}],
    parent_nodes:[{'id':1,'order_inside':0}],

    slug:null,

    parent_id:null,
});

function submit(){
    form.post(route('admin.categories.store'));
}

</script>
<template>
    <div class="">
        <h1 class="mt-8 ml-10 lg:text-5xl md:text-4xl md:text-left text-center text-3xl font-semibold md:mb-20 mb-12">
            Create Category
        </h1>

        <form  @submit.prevent="submit" class="bg-gray-200">
            Form
            <!--      Title      -->
            <label>Title
                <input v-model="form.title" type="text">
            </label>

            <!--    Parent Nodes      -->
            <div v-for="parent_node in form.parent_nodes">
                <label>Path
                    <input v-model="parent_node.id" type="text">
                    <input v-model="parent_node.order_inside" type="text">
                </label>
            </div>

            <!--      Slug      -->
            <label>Slug
                <input v-model="form.slug" type="text">
            </label>

            <!--      Submit Button      -->
            <button @click="submit">Submit</button>
        </form>
    </div>
</template>
