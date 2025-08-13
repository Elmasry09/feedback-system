<template>
    <AuthenticatedLayout>
        <div class="relative overflow-x-auto">
            <div class="flex justify-end mb-4">
                <router-link to="/add-order">

                    <button class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700 transition">
                        Add Order
                    </button>
                </router-link>
            </div>

            <table class="w-full table-fixed text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3 w-1/4">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3 w-1/4">
                            Phone
                        </th>
                        <th scope="col" class="px-6 py-3 w-1/4">
                            Created at
                        </th>
                        <th scope="col" class="px-6 py-3 w-1/4">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(order, index) in orders" :key="index"
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ order.name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ order.phone }}
                        </td>
                        <td class="px-6 py-4">
                            {{ new Date(order.created_at).toLocaleDateString() }}
                        </td>
                        <td class="px-6 py-4">
                            <button @click="deleteOrder(order.id)"
                                class="bg-red-500 text-white px-3 py-1 rounded-md text-xs md:text-sm">
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { initFlowbite } from 'flowbite';
import { onMounted, watch } from 'vue';
import AuthenticatedLayout from '../Layouts/AuthenticatedLayout.vue';
import gql from 'graphql-tag';
import { useQuery } from '@vue/apollo-composable';
import { computed } from 'vue';
import { useMutation } from '@vue/apollo-composable'
import { useApolloClient } from '@vue/apollo-composable'
import Swal from 'sweetalert2';
import { useRouter } from 'vue-router';


const { client } = useApolloClient()

const OrdersQuery = gql`
query Orders {
    orders {
        id
        name
        phone
        created_at
    }
}`;

const router = useRouter();

const { result, loading, error, refetch } = useQuery(OrdersQuery);
const orders = computed(() => result.value?.orders || []);

watch(error, () => {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: error.value.message,
    });
})


const deleteOrder = async (id) => {
    try {
        const response = await client.mutate({
            mutation: gql`
                mutation deleteOrder($id: ID!) {
                    deleteOrder(id: $id) {
                        message
                    }
                }
            `,
            variables: { id }
        });
        await refetch();
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Order deleted successfully!',
        });
    } catch (err) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: `Failed to delete order: ${err.message}`,
        });
    }

};


onMounted(() => {
    initFlowbite();
})

</script>
