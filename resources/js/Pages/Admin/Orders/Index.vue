<script setup>
import {router, useForm} from "@inertiajs/vue3";
import Pagination from "../../Categories/Partials/Pagination.vue";
import TrashIcon from "../../../Svg/TrashIcon.vue";
import {ref} from "vue";

let props = defineProps({orders: Object, search:String});
let openedCustomer = ref(null);
let openedCart = ref(null);

let form = useForm({
    search:props.search ?? '',
});

function submit(){form.get('',{preserveScroll: true, preserveState: true,});}
</script>
<script>
import AdminLayout from "../../../Layouts/AdminLayout.vue";

export default {
    layout: AdminLayout,
}
</script>
<template>
    <div class="mx-auto px-9 max-w-[1656px] pb-12">
        <h1 class="mt-10 text-4xl font-semibold">Заказы</h1>

        <div class="my-4 mx-auto w-fit">
            <div><input @input="submit" v-model="form.search" type="text"></div>
            <Pagination :links="orders.links" class="mt-3 mb-4"/>

            <!-- Orders Table -->
            <table @click="openedCustomer = null;openedCart = null;" class="mt-6">
                <thead class="text-xs">
                <tr>
                    <th>Id</th>
                    <th>Customer Info</th>
                    <th>Cart</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Delete</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="order in orders.data">
                    <td>{{ order.id }}</td>
                    <td>
                        <div @click.stop="openedCustomer = (openedCustomer === null) ? order.id : null" class="cursor-pointer">{{ (customer = JSON.parse(order.customer_info)).name }}</div>
                        <div v-if="order.id === openedCustomer" class="absolute border rounded p-2 bg-ui-body">
                            <div>{{ customer.name }}</div>
                            <div>{{ customer.phone }}</div>
                            <div>{{ customer.email }}</div>
                        </div>
                    </td>
                    <td>
                        <div @click.stop="openedCart = (openedCart === null) ? order.id : null" class="cursor-pointer">{{ (cart = JSON.parse(order.cart)).orderSum }}</div>
                        <div v-if="order.id === openedCart" class="absolute border rounded p-2 bg-ui-body">
                            <div>{{ cart.products }}</div>
                        </div>
                    </td>
<!--                    <td>{{ JSON.parse(order.cart)['orderSum'] }}</td>-->
                    <td>{{ order.status ?? 'new' }}</td>
                    <td class="text-xs">{{ order.created_at }}</td>
                    <td class="text-xs">{{ order.updated_at }}</td>
                    <td class="text-center">
                        <button @click="router.delete(route('admin.orders.destroy', order.id))">
                            <TrashIcon class="w-5 fill-ui-text-accent"/>
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<style scoped>
table th,
table td {
    padding: 3px 5px;
    border: 1px solid gainsboro;
}
</style>
