<script setup>
import {useForm} from "@inertiajs/vue3";
import ExpandIcon from "../../../Svg/ExpandIcon.vue";
import CollapseIcon from "../../../Svg/CollapseIcon.vue";
import CheckedIcon from "../../../Svg/CheckedIcon.vue";
import {ref} from "vue";

let props = defineProps({
    form: Object,
    name: String,
    field: String,
    options: Object,
});

let isListOpen = ref(false);

const emit = defineEmits();

function toggleOption(option) {
    props.form.params ??= {};
    props.form.params[props.field] ??= [];

    if(props.form.params[props.field].includes(option)){
        props.form.params[props.field] = props.form.params[props.field].filter(item => item !== option)
    }else {
        props.form.params[props.field].push(option)
    }

    emit('submit')
}
</script>

<template>
    <!--  Select List  -->
    <div class="text-lg">
        <button @click.prevent="isListOpen=!isListOpen" class="my-2 flex items-center gap-4">
            <div class="py-1 border-b border-dashed border-ui-text-accent text-left">{{ name }}</div>
            <ExpandIcon  v-if="isListOpen" class="w-6"/>
            <CollapseIcon v-else class="w-6"/>
        </button>

        <!-- OPTIONS LIST -->
        <div v-if="isListOpen" class="overflow-y-auto max-h-[250px] flex flex-col gap-3.5">
            <label @click="toggleOption(option)" class="flex items-center gap-4" v-for="option in options">
                <!-- Checkbox Square -->
                <div class="w-5 h-5 border border-ui-text-secondary rounded shrink-0">
                    <CheckedIcon v-if="form.params[field]?.includes(option)" class="-mt-0.5 w-4.5 fill-ui-text-accent"/>
                </div>
                {{ option }}
            </label>
        </div>
    </div>
</template>
