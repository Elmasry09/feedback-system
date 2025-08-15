<template>
    <AuthenticatedLayout>
        <main id="content" role="main" class="w-full  max-w-md mx-auto p-6">
            <div
                class="mt-7 bg-white  rounded-xl shadow-lg dark:bg-gray-800 dark:border-gray-700 border-2">
                <div class="p-4 sm:p-7">
                    <div class="text-center">
                        <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">Add Order </h1>
                    </div>

                    <div class="mt-5">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid gap-y-4">
                                <div>
                                    <label for="name"
                                        class="block text-sm font-medium mb-2 dark:text-white">Order Name</label>
                                    <div class="relative">
                                        <input type="text" v-model="order.name" placeholder="Order Name"
                                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                            required>
                                    </div>
                                </div>
                                <div>
                                    <label for="phone"
                                        class="block text-sm font-medium mb-2 dark:text-white">Phone</label>
                                    <div class="relative">
                                        <input type="text" v-model="order.phone" placeholder="123-456-7890"
                                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                            required>
                                    </div>
                                </div>
                                <button type="submit"
                                    class="py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-blue-500 text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all text-sm dark:focus:ring-offset-gray-800">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '../Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import gql from 'graphql-tag';
import { useApolloClient } from '@vue/apollo-composable'
import Swal from 'sweetalert2';


const router = useRouter();
const { client } = useApolloClient()
const order = ref({
    name: '',
    phone: ''
});

const submit = async () => {
    try {
        const response = await client.mutate({
            mutation: gql`
                mutation addOrder($name: String!, $phone: String!) {
                    createOrder(name: $name, phone: $phone) {
                        name
                        phone
                    }
                }
            `,
            variables: { name: order.value.name, phone: order.value.phone }
        });
        router.push({ name: 'orders' , state: { refresh: true } });
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Order added successfully!',
        });
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: `Failed to add order: ${error.message}`,
        });
    }
};


</script>