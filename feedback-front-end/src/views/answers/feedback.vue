<template>
    <GuestLayout>
        <div class="max-w-3xl mx-auto p-4">
            <h2 class="text-xl font-bold mb-4 text-center">Questions</h2>
            <loadingComponent v-if="loading" />
            <noContent v-if="noData" word="questions" />


            <form @submit.prevent="submit" v-if="questions.length > 0" class="space-y-6">
                <div class="bg-white shadow-md rounded p-4">
                    <label class="block text-gray-700 font-medium mb-2">
                        Phone
                    </label>
                    <input type="text" v-model="phone" placeholder="Enter your phone number"
                        class="w-full border border-gray-300 rounded px-3 py-2" />

                    <div v-for="(question, index) in questions" :key="question.id" class="mt-4">
                        <label class="block text-gray-700 font-medium mb-2">
                            {{ question.text }}
                        </label>
                        <input type="text" v-model="answers[index].answer" placeholder="Your answer here"
                            class="w-full border border-gray-300 rounded px-3 py-2" />
                    </div>
                </div>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
                    Submit Answers
                </button>
            </form>
        </div>
    </GuestLayout>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import { useQuery, useMutation } from "@vue/apollo-composable";
import gql from "graphql-tag";
import GuestLayout from "../Layouts/GuestLayout.vue";
import { useApolloClient } from "@vue/apollo-composable";
import Swal from "sweetalert2";
import noContent from '@/components/noContent.vue';
import loadingComponent from '@/components/loading.vue';

const { client } = useApolloClient();

const questionsQuery = gql`
  query Questions {
    questions {
      id
      text
      created_at
    }
  }
`;

const answers = ref([]);
const phone = ref('')
const noData = ref(false);
const { result, loading, error } = useQuery(questionsQuery);
const questions = computed(() => result.value?.questions || []);

watch(error, () => {
    if (error.value) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.value.message,
        });
    }
})


watch(questions, (newQuestions) => {
    if (newQuestions.length) {
        answers.value = newQuestions.map((q) => ({
            question_id: q.id,
            answer: "",
        }));
    }
});

watch(result, () => {
    if (result.value?.questions.length === 0) {
        noData.value = true;
    } else {
        noContent.value = false;
    }
})

const submit = async () => {
    try {
        const response = await client.mutate({
            mutation: gql`
                mutation CreateAnswer($phone: String!, $answersQuestions: [AnswerInput!]!) {
                    createAnswer(phone: $phone, answersQuestions: $answersQuestions) {
                        id
                        order_id
                        created_at
                        updated_at
                    }
                }
            `,
            variables: {
                phone: phone.value,
                answersQuestions: answers.value,
            },
        });

        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Your answers have been submitted successfully!',
        });
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: `Failed to submit answers: ${error.message}`,
        });
    }
};

</script>
