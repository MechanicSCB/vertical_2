<script setup>
import {router} from "@inertiajs/vue3";
import Pagination from "../../Categories/Partials/Pagination.vue";
import TrashIcon from "../../../Svg/TrashIcon.vue";
import {useForm} from "@inertiajs/vue3";

let props = defineProps({categories: Object, search:String})

let form = useForm({
    search:props.search ?? '',
});

function submit(){
    form.get('',{
        preserveScroll: true,
        preserveState: true,
    });
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
        <h1>Категории (всего: {{categories.total}})</h1>
        <Link :href="route('admin.categories.create')" class="px-3 py-1 rounded bg-green-600 text-white">Создать категорию</Link>
    </div>

    <div class="my-4 mx-auto w-fit">
        <div><input @input="submit" v-model="form.search" type="text"></div>
        <Pagination :links="categories.links" class="mt-3 mb-4"/>
        <!-- Categories Table -->
        <table class="table-fixed">
            <thead class="text-xs"><tr><th>Id</th><th>Nodes</th><th>Image</th><th>Title</th><th>Slug</th><th>Created At</th><th>Updated At</th><th>Delete</th></tr></thead>
            <tbody>
                <tr v-for="category in categories.data">
                    <td>{{ category.id }}</td>
                    <td>
                        <div class="flex flex-wrap gap-1 justify-center">
                            <div v-for="node in category.nodes">
                                <Link :href="node.url">
                                    <div class="rounded-full border w-5 h-5 flex items-center justify-center">
                                        {{node.level}}
                                    </div>
                                </Link>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="mx-auto w-12 h-12 flex items-center">
                            <img class="mx-auto max-h-full" :src="'/storage/images/categories/'+category.id+'.jpg'" alt="">
                        </div>
                    </td>
                    <td><Link :href="route('admin.categories.edit', category.id)">{{ category.title }}</Link></td>
                    <td>{{ category.slug }}</td>
                    <td class="text-xs">{{ category.created_at }}</td>
                    <td class="text-xs">{{ category.updated_at }}</td>
                    <td class="text-center">
                        <button @click="router.delete(route('admin.categories.destroy', category.id))">
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
