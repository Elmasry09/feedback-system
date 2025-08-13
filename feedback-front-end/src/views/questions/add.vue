<template>
    <AuthenticatedLayout>
        <main id="content" role="main" class="w-full  max-w-md mx-auto p-6">
            <div
                class="mt-7 bg-white  rounded-xl shadow-lg dark:bg-gray-800 dark:border-gray-700 border-2 border-indigo-300">
                <div class="p-4 sm:p-7">
                    <div class="text-center">
                        <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">Add Question </h1>
                    </div>

                    <div class="mt-5">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid gap-y-4">
                                <div>
                                    <label for="question"
                                        class="block text-sm font-bold ml-1 mb-2 dark:text-white">Question</label>
                                    <div class="relative">
                                        <input type="question" v-model="question" id="question" placeholder="Example"
                                            name="question"
                                            class="py-3 px-4 block w-full border-2 border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm"
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
const question = ref('');

const submit = async () => {
    try {
        const response = await client.mutate({
            mutation: gql`
                mutation addQuestion($text: String!) {
                    createQuestion(text: $text) {
                        text
                    }
                }
            `,
            variables: { text: question.value }
        });
        router.push('/questions');
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Question added successfully!',
        });
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: `Failed to add question: ${error.message}`
        });
    }
};


</script>