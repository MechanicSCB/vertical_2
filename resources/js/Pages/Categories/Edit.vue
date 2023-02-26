<script setup>
import {useForm} from "@inertiajs/vue3"

let props = defineProps({category:Object});

let form = useForm({
    title:props.category.title,
});

function submit(){
    form.patch(route('categories.update',props.category));
}

</script>
<template>
    <div class="">
        <h1 class="mt-8 ml-10 lg:text-5xl md:text-4xl md:text-left text-center text-3xl font-semibold md:mb-20 mb-12">
            Edit Category
        </h1>

        <form  @submit.prevent="submit" class="bg-gray-200">
            Form
            <!--      Title      -->
            <label>Title
                <input v-model="form.title" type="text">
            </label>

            <!--      Submit Button      -->
            <button @click="submit">Submit</button>
        </form>
    </div>
</template>
