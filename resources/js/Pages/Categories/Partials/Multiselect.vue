<script setup>
import {useForm} from "@inertiajs/vue3";
import ExpandIcon from "../../../Svg/ExpandIcon.vue";
import CollapseIcon from "../../../Svg/CollapseIcon.vue";
import CheckedIcon from "../../../Svg/CheckedIcon.vue";
import {ref} from "vue";
import CloseIcon from "../../../Svg/CloseIcon.vue";

let props = defineProps({
    form: Object,
    name: String,
    field: String,
    options: Object,
});

let isListOpen = ref(false);
let search = ref('');

const emit = defineEmits();

function toggleOption(option) {
    props.form.params ??= {};
    props.form.params[props.field] ??= [];

    if(props.form.params[props.field].includes(option)){
        props.form.params[props.field] = props.form.params[props.field].filter(item => item !== option)
    }else {
        props.form.params[props.field].push(option)
    }

    emit('submit');
    search.value = '';
}

function clearSelect() {
    props.form.params[props.field] = []
    emit('submit');
    search.value = '';
}

function filteredOptions() {
    return props.options.filter(v => v.value.toLowerCase().includes(search.value.toLowerCase()))
        .sort((a, b) => a.value > b.value)
        // .sort((a, b) => props.form.params[props.field]?.includes(b.value) - props.form.params[props.field]?.includes(a.value))
}
</script>

<template>
    <!--  Select List  -->
    <div class="text-lg">
        <div class="flex gap-2">
            <!-- Expand/Collapse Button  -->
            <button @click.prevent="isListOpen=!isListOpen" class="my-2 flex items-center gap-4">
                <!-- Param name -->
                <div class="py-1 border-b border-dashed border-ui-text-accent text-left">{{ name }}</div>

                <!-- Expand/CollapseIcon  -->
                <ExpandIcon  v-if="isListOpen" class="w-6"/>
                <CollapseIcon v-else class="w-6"/>
            </button>

            <!-- Selected Options -->
            <div class="flex items-center gap-x-1 flex-wrap">
                <div v-for="selected in form.params[field]" @click="toggleOption(selected)"
                     class="text-xs flex h-fit items-center group cursor-pointer text-ui-text-secondary rounded border
                     hover:text-ui-text-accent pl-1 hover:border-ui-text-accent"
                >
                    <div>{{selected}}</div>
                    <CloseIcon class="w-3 fill-ui-text-light group-hover:fill-ui-text-accent"/>
                </div>
                <CloseIcon v-if="form.params[field]?.length > 3" @click="clearSelect"
                           class="w-5 rounded-full cursor-pointer fill-ui-text-accent_inverse bg-ui-secondary hover:bg-ui-accent"/>
            </div>
        </div>

        <!-- OPTIONS LIST -->
        <div v-if="isListOpen" class="max-h-[250px] flex flex-col gap-3.5">
            <!-- SEARCH INPUT -->
            <input v-if="search !== '' || filteredOptions().length > 10" v-model="search" type="text" class="border-none !ring-0 rounded bg-ui-light text-ui-text-secondary mr-3">

            <div class="h-full overflow-y-auto">
                <label @click="toggleOption(option.value)" class="flex items-center gap-4" v-for="option in filteredOptions()">
                    <!-- Checkbox Square -->
                    <div class="w-5 h-5 border border-ui-text-secondary rounded shrink-0">
                        <CheckedIcon v-if="form.params[field]?.includes(option.value)" class="-mt-0.5 w-4.5 fill-ui-text-accent"/>
                    </div>
                    {{ option.value }} ({{option.filtered_count ?? 'n/a'}} из {{option.node_count}})
                </label>
            </div>
        </div>
    </div>
</template>
