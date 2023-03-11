<script setup>
let props = defineProps({links: Array})

function getLabel(label,index){
    if(index===0) return '<';
    if(index===props.links.length - 1) return '>';

    return label;
}
</script>
<template>
    <div class="flex">
        <Component
            :is="link.url && !link.active ? 'Link' : 'span'"
            v-for="(link, index) in links"
            :href="link.url"
            v-html="getLabel(link.label,index)"
            class="w-9 text-center py-2 text-xl hover:text-ui-text-accent"
            :class="{
                ' !text-ui-text-secondary ' : ! link.url,
                ' text-ui-text-accent font-semibold ' : link.active,
                ' text-ui-text-accent transition-all hover:pr-1' : index===0 && link.url,
                ' text-ui-text-accent transition-all hover:pl-1' : (index===links.length - 1) && link.url,
            }"
            preserve-scroll
            preserve-state
        />
    </div>
</template>
