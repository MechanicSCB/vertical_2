<script setup>
import {router} from "@inertiajs/vue3";
import Pagination from "../../Categories/Partials/Pagination.vue";
import TrashIcon from "../../../Svg/TrashIcon.vue";
import {useForm} from "@inertiajs/vue3";

let props = defineProps({products: Object, search:String})

let form = useForm({
    search:props.search ?? '',
});

function submit(){
    form.get('',{preserveScroll: true, preserveState: true});
}
</script>
<script>
import AdminLayout from "../../../Layouts/AdminLayout.vue";

export default {
    layout: AdminLayout,
}
</script>
<template>
    <Head title="Админ-панель: Категории"/>
    <div class="mt-6 flex justify-between">
        <h1>Товары (всего: {{products.total}})</h1>
        <Link :href="route('admin.products.create')" class="px-3 py-1 rounded bg-green-600 text-white">Создать товар</Link>
    </div>

    <div class="my-4 mx-auto w-fit">
        <div><input @input="submit" v-model="form.search" type="text"></div>
        <Pagination :links="products.links" class="mt-3 mb-4"/>
        <!-- Products Table -->
        <table class="table-fixed">
            <thead class="text-xs"><tr><th>Id</th><th>Image</th><th>Name</th><th>Description</th><th>Params</th><th>Created At</th><th>Updated At</th><th>Delete</th></tr></thead>
            <tbody>
                <tr v-for="product in products.data">
                    <td>{{ product.id }}</td>
                    <td>
                        <div class="mx-auto w-12 h-12 flex items-center">
                            <img class="mx-auto max-h-full" :src="'/storage/images/products/s220/'+product.code+'.jpg'" alt="">
                        </div>
                    </td>
                    <td><Link :href="route('admin.products.edit', product.slug)">{{ product.name }}</Link></td>
                    <td style="padding: 0"><div class="px-1 max-h-20 overflow-y-auto text-sm" v-html="product.description"></div></td>
                    <td style="padding: 0">
                        <div class="px-1 max-h-20 overflow-y-auto text-xs">
                            <div v-for="(value,param) in JSON.parse(product.params)" class="border-t">{{param}} - {{value}}</div>
                        </div>
                    </td>
                    <td class="text-xs">{{ product.created_at }}</td>
                    <td class="text-xs">{{ product.updated_at }}</td>
                    <td class="text-center">
                        <button @click="router.delete(route('admin.products.destroy', product.slug))">
                            <TrashIcon class="w-5 fill-ui-text-accent"/>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</template>
<style scoped>
table th,
table td {
    padding: 3px 5px;
    border: 1px solid gainsboro;
}
</style>
