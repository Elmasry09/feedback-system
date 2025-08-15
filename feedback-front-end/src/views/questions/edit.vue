<template>
    <div v-if="visible" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 px-4">
        <div class="relative w-full max-w-lg mx-auto">
            <div class="relative bg-white rounded-lg shadow-lg dark:bg-gray-800">
                <!-- Header -->
                <div
                    class="flex items-center justify-between p-4 md:p-5 border-b border-gray-200 dark:border-gray-600 rounded-t">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Edit Question
                    </h3>
                    <button @click="$emit('close')" type="button"
                        class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg w-8 h-8 flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white transition">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M1 1l6 6m0 0l6 6M7 7l6-6M7 7L1 13" />
                        </svg>
                    </button>
                </div>

                <!-- Content -->
                <div class="p-4 md:p-6">
                    <form @submit.prevent="submit" class="space-y-4">
                        <!-- Question Text -->
                        <div>
                            <label for="text" class="block text-sm font-medium mb-2 dark:text-white">
                                Question
                            </label>
                            <textarea v-model="form.text" placeholder="Question Text"
                                class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                required rows="4"></textarea>
                        </div>
                        <div class="pt-2">
                            <button type="submit"
                                class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                Update Question
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
import { useApolloClient } from '@vue/apollo-composable'
import Swal from 'sweetalert2';

const props = defineProps({
    visible: Boolean,
    question: Object
})

const emit = defineEmits(['close', 'updated'])

const { client } = useApolloClient()

const form = ref({
    id: null,
    text: ''
});

watch(() => props.question, (newQuestion) => {
    if (newQuestion) {
        form.value = { ...newQuestion };
    }
}, { deep: true, immediate: true });

const submit = async () => {
    if (!form.value.id) return;
    try {
        await client.mutate({
            mutation: gql`
                mutation updateQuestion($id: ID!, $text: String!) {
                    updateQuestion(id: $id, text: $text) {
                        id
                        text
                    }
                }
            `,
            variables: {
                id: form.value.id,
                text: form.value.text
            }
        });
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Question updated successfully!',
        });
        emit('updated');
        emit('close');
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: `Failed to update question: ${error.message}`,
        });
    }
};
</script>
