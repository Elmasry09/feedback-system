<template>
    <AuthenticatedLayout>
        <main id="content" role="main" class="w-full  max-w-md mx-auto p-6">
            <div class="mt-7 bg-white  rounded-xl shadow-lg dark:bg-gray-800 dark:border-gray-700 border-2">
                <div class="p-4 sm:p-7">
                    <div class="text-center">
                        <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">Add Question </h1>
                    </div>
                    <div class="mt-5">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid gap-y-4">
                                <div>
                                    <div>
                                        <label for="question"
                                            class="block text-sm font-medium mb-2 dark:text-white">Question</label>
                                        <div class="relative">
                                            <input type="text" v-model="question.text" id="question"
                                                placeholder="Example" name="question"
                                                class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                                required>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <label for="question"
                                            class="block text-sm font-medium mb-2 dark:text-white">Type</label>
                                        <div class="relative">
                                            <select v-model="question.type"
                                                class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                <option disabled selected value="">Choose a type</option>
                                                <option value="text">text</option>
                                                <option value="choice">choice</option>
                                                <option value="rating">rating</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mt-2" v-if="question.type === 'choice'">
                                        <div class="flex items-center justify-between mb-2">
                                            <label for="question" class="block text-sm font-medium dark:text-white">
                                                Choices
                                            </label>
                                            <button type="button" @click="addChoice"
                                                class="px-3 py-1 text-sm rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                                                + Add Choice
                                            </button>
                                        </div>
                                        <div v-for="(choice, index) in question.choices" :key="index" class="relative">
                                            <input type="text" v-model="question.choices[index]" id="question"
                                                placeholder="Example" name="question"
                                                class="py-3 px-4 mb-3 block w-full border-gray-200 rounded-lg text-sm  focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                                required>
                                        </div>
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
const question = ref({
    text: '',
    type: '',
    choices: [
        ''
    ]
});

const addChoice = () => {
    question.value.choices.push('');
};


const submit = async () => {
    try {
        const response = await client.mutate({
            mutation: gql`
                mutation addQuestion($text: String! , $type: String! , $choices: [String!]) {
                    createQuestion(text: $text , type: $type , choices: $choices) {
                        text
                    }
                }
            `,
            variables: { text: question.value.text, type: question.value.type, choices: question.value.type === 'choice' ? question.value.choices : null }
        });
        router.push({ name: 'questions', state: { refresh: true } });
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