<template>
    <div v-if="visible" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 px-4">
        <div class="relative w-full  max-w-lg mx-auto">
            <div class="relative bg-white rounded-lg shadow-lg dark:bg-gray-800 
                max-h-[80vh] flex flex-col">
                <div
                    class="flex items-center justify-between p-4 md:p-5 border-b border-gray-200 dark:border-gray-600 rounded-t">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Edit Order
                    </h3>
                    <button @click="$emit('close')" type="button"
                        class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg w-8 h-8 flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white transition">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M1 1l6 6m0 0l6 6M7 7l6-6M7 7L1 13" />
                        </svg>
                    </button>
                </div>
                <div class="p-4 md:p-6 max-h-[600px] overflow-y-auto">
                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium mb-2 dark:text-white">Order Name</label>
                            <input type="text" v-model="form.name" placeholder="Order Name"
                                class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                required />
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium mb-2 dark:text-white">Phone</label>
                            <input type="text" v-model="form.phone" placeholder="123-456-7890"
                                class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                required />
                        </div>
                        <div>
                            <label for="product_name" class="block text-sm font-medium mb-2 dark:text-white">Product
                                Name</label>
                            <input type="text" v-model="form.product_name" placeholder="Product Name"
                                class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                required />
                        </div>
                        <div>
                            <div class="flex items-center justify-center w-full">
                                <label for="dropzone-file"
                                    class="flex flex-col items-center justify-center w-full h-64 border-2 
                                                      border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 
                                                       dark:bg-gray-700 hover:bg-gray-100 
                                                      dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 
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
                                    <input id="dropzone-file" type="file" class="hidden" @change="onFileChange" />
                                </label>
                            </div>
                        </div>
                        <div class="pt-2">
                            <button type="submit"
                                class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                Update Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import gql from 'graphql-tag';
import { useApolloClient } from '@vue/apollo-composable';
import Swal from 'sweetalert2';

const props = defineProps({
    visible: Boolean,
    order: Object
});

const emit = defineEmits(['close', 'updated']);
const { client } = useApolloClient();

const form = ref({
    id: null,
    name: '',
    phone: '',
    product_name: '',
    image: null
});

watch(() => props.order, (newOrder) => {
    if (newOrder) {
        form.value = { ...newOrder };
    }
}, { deep: true, immediate: true });

const onFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        const file = event.target.files[0];
        form.value.image = file || null;
    }
};

const submit = async () => {
    try {
        await client.mutate({
            mutation: gql`
        mutation updateOrder($id: ID!, $name: String, $phone: String, $product_name: String, $image: Upload) {
          updateOrder(id: $id, name: $name, phone: $phone, product_name: $product_name, image: $image) {
            id
            name
            phone
            product_name
          }
        }`,
            variables: {
                id: form.value.id,
                name: form.value.name,
                phone: form.value.phone,
                product_name: form.value.product_name,
                image: form.value.image
            }
        });

        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Order updated successfully!',
        });

        emit('updated');
        emit('close');
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: `Failed to update order: ${error.message}`,
        });
    }
};
</script>
