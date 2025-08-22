<template>
    <AuthenticatedLayout>
        <main id="content" role="main" class="w-full max-w-md mx-auto p-6">
            <div class="mt-7 bg-white rounded-xl shadow-lg dark:bg-gray-800 dark:border-gray-700 border-2">
                <div class="p-4 sm:p-7">
                    <div class="text-center">
                        <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">Add Order</h1>
                    </div>

                    <div class="mt-5">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid gap-y-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium mb-2 dark:text-white">Customer
                                        Name</label>
                                    <input type="text" v-model="form.name" placeholder="Order Name" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm 
                                           focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 
                                           dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" required>
                                </div>

                                <div>
                                    <label for="phone"
                                        class="block text-sm font-medium mb-2 dark:text-white">Phone</label>
                                    <input type="text" v-model="form.phone" placeholder="123-456-7890" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm 
                                           focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 
                                           dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" required>
                                </div>

                                <div>
                                    <label for="product_name"
                                        class="block text-sm font-medium mb-2 dark:text-white">Product Name</label>
                                    <input type="text" v-model="form.product_name" placeholder="Coffee Mug" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm 
                                           focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 
                                           dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" required>
                                </div>

                                <!-- File Upload -->
                                <div>
                                    <div class="flex items-center justify-center w-full">
                                        <label for="dropzone-file"
                                            class="flex flex-col items-center justify-center w-full h-64 border-2 
                                                      border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 
                                                       dark:bg-gray-700 hover:bg-gray-100 
                                                      dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400"
                                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 20 16">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 
                                                             16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 
                                                             5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 
                                                             0L8 8m2-2 2 2" />
                                                </svg>
                                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                                    <span class="font-semibold">Click to upload</span> or drag and drop
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    SVG, PNG, JPG or GIF (MAX. 800x400px)
                                                </p>
                                            </div>
                                            <input id="dropzone-file" type="file" class="hidden"
                                                @change="onFileChange" />
                                        </label>
                                    </div>
                                </div>
                                <button type="submit" class="py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md 
                                        border border-transparent font-semibold bg-blue-500 text-white hover:bg-blue-600 
                                        focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 
                                        transition-all text-sm dark:focus:ring-offset-gray-800">
                                    Submit
                                </button>
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

const form = ref({
    name: '',
    phone: '',
    product_name: '',
    image: null
});

const onFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        const file = event.target.files[0];
        form.value.image = file || null;
    }
};

const submit = async () => {
    try {
        const response = await client.mutate({
            mutation: gql`
                mutation addOrder($name: String!, $phone: String!, $product_name: String!, $image: Upload) {
                    createOrder(name: $name, phone: $phone, product_name: $product_name, image: $image) {
                        id
                        name
                        phone
                    }
                }
            `,
            variables: {
                name: form.value.name,
                phone: form.value.phone,
                product_name: form.value.product_name,
                image: form.value.image
            }
        });

        router.push({ name: 'orders', state: { refresh: true } });
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
