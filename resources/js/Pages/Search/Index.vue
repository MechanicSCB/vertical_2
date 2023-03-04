<script setup>
import {useForm} from "@inertiajs/vue3";

let form = useForm({
    search:'',
});

</script>
<template>
    <div>
        <div>Search</div>
        <!-- SEARCH INPUT -->
        <input @input="submit" v-model="form.search" type="text" placeholder="Что вы хотите найти?"
               class=" mt-3 w-full border-none !ring-0 rounded bg-ui-light text-ui-text-secondary">
    </div>

</template>
