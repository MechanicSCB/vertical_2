<script setup>
import {useForm} from "@inertiajs/vue3"

let props = defineProps({node:Object});

let form = useForm({
    parent_id:props.node.parent_id,
    is_copy:false,
});

function submit(){
    form.patch(route('nodes.update',props.node));
    // if(form.is_copy){
    //     form.post(route('nodes.copy',props.node));
    // }else {
    //     form.patch(route('nodes.update',props.node));
    // }
}

</script>
<template>
    <div class="">
        <h1 class="mt-8 ml-10 lg:text-5xl md:text-4xl md:text-left text-center text-3xl font-semibold md:mb-20 mb-12">
            Edit Category Node
        </h1>

        <form  @submit.prevent="submit" class="bg-gray-200">
            Form
            <label>Is Copy
                <input v-model="form.is_copy" type="checkbox">
            </label>

            <!--      Parent Node      -->
            <label>Parent_id
                <input v-model="form.parent_id" type="text">
            </label>

            <!--      Submit Button      -->
            <button @click="submit">Submit</button>
        </form>
    </div>
</template>
