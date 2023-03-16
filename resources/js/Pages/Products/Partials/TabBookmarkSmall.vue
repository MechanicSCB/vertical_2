<script setup>
import {inject} from "vue";
import ExpandIcon from "../../../Svg/ExpandIcon.vue";
import CollapseIcon from "../../../Svg/CollapseIcon.vue";

let props = defineProps({tabName: Object});

let activeTab = inject('activeTab');
</script>

<template>
    <div @click="activeTab=activeTab===tabName?null:tabName"
         class="pt-1 md:hidden block text-2xl border-b border-ui-text-primary pb-3 flex flex-row justify-between"
        :class="activeTab===tabName ? 'text-ui-text-accent mb-6' : 'text-ui-text-primary'"
    >
        <div>
            <slot/>
        </div>
        <ExpandIcon v-if="activeTab===tabName" class="w-6"/>
        <CollapseIcon v-else class="w-6"/>
    </div>
</template>

<style scoped>

</style>
