<template>
    <GuestLayout>
        <div v-if="ifAnwerExist == false" class="max-w-full mx-auto p-4">
            <h2 class="text-xl font-bold mb-4 text-center">Questions</h2>
            <loadingComponent v-if="loading" />
            <noContent v-if="noData" word="data" />
            <div class="flex justify-between" v-if="questions.length > 0 && order">
                <div class="w-1/3 p-3" v-if="order.image">
                    <div>
                        <article
                            class="mx-auto my-4 flex w-full flex-col overflow-hidden rounded-2xl border border-gray-300 bg-white text-gray-900 transition hover:translate-y-2 hover:shadow-lg">
                            <div>
                                <img :src="order.image" class="h-56 w-full object-cover" alt="" />
                                <div class="flex-auto px-6 py-5">
                                    <span class="mb-2 flex items-center text-sm font-semibold">
                                        <svg class="mr-1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
                                            role="img" width="1em" height="1em" preserveAspectRatio="xMidYMid meet"
                                            viewBox="0 0 24 24">
                                            <path fill="none" stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="1.5"
                                                d="M14.272 10.445L18 2m-8.684 8.632L5 2m7.761 8.048L8.835 2m5.525 0l-1.04 2.5M6 16a6 6 0 1 0 12 0a6 6 0 0 0-12 0Z" />
                                        </svg>
                                        {{ order.product_name }}</span>
                                    <h3 class="mt-4 mb-3 text-xl font-semibold xl:text-2xl">Dear {{ order.name }} what
                                        is your comment on our services and products?</h3>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
                <div :class="{ 'w-2/3': order.image, 'w-full': !order.image }" class="p-3">
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="bg-white shadow-md rounded p-4">
                            <label class="block text-sm font-medium mb-2 dark:text-white">
                                Phone
                            </label>
                            <input type="text" v-model="phone" required placeholder="Enter your phone number"
                                class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" />
                            <div v-for="(question, index) in questions" :key="question.id" class="mt-4">
                                <label class="block text-sm font-medium mb-2 dark:text-white">
                                    {{ question.text }}
                                </label>
                                <div v-if="question.type == 'text'">
                                    <input type="text" required v-model="answers[index].answer"
                                        placeholder="Your answer here"
                                        class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" />
                                </div>
                                <div v-else-if="question.type == 'rating'">
                                    <div class="rating">
                                        <input v-for="n in 5" required :key="n" type="radio" name="rating-2"
                                            class="mask mask-star-2 bg-orange-400 text-orange-400" :value="String(n)"
                                            v-model="answers[index].answer" :aria-label="`${n} star`" />
                                    </div>
                                </div>
                                <div v-else>
                                    <div class="relative">
                                        <select required v-model="answers[index].answer"
                                            class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option disabled selected value="">Choose answer</option>
                                            <option v-for="(choice, index) in question.choices" :key="index"
                                                :value="choice">{{
                                                    choice }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
                                Submit Answers
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div v-else class="flex flex-col items-center justify-center min-h-screen">
            <h2 class="text-2xl font-bold mb-4 text-center">You have already submitted your answers.</h2>
            <p class="text-gray-600">Thank you for your feedback!</p>
        </div>
    </GuestLayout>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import { useQuery } from "@vue/apollo-composable";
import gql from "graphql-tag";
import GuestLayout from "../Layouts/GuestLayout.vue";
import { useApolloClient } from "@vue/apollo-composable";
import Swal from "sweetalert2";
import noContent from '@/components/noContent.vue';
import loadingComponent from '@/components/loading.vue';
import { useRoute } from "vue-router";

const route = useRoute();
const { client } = useApolloClient();

const ifAnwerExist = ref(false);

const OrderWithQuestionsQuery = gql`
  query OrderWithQuestions($slug: String!) {
    allquestions {
      id
      text
      type
      choices
      created_at
    }
    order(slug: $slug) {
      id
      phone
      image
      name
      product_name
    }
  }
`;

const answers = ref([]);
const phone = ref('');
const noData = ref(false);
const { result, loading, error } = useQuery(OrderWithQuestionsQuery, {
    slug: route.params.slug
});
const order = computed(() => result.value?.order || null);
const questions = computed(() => result.value?.allquestions || []);

watch(error, () => {
    if (error.value.message == 'This order already has an answer.') {
        ifAnwerExist.value = true;
        Swal.fire({
            icon: 'warning',
            title: 'Warning',
            text: 'sorry, you have already submitted your answers for this order. Thank you!',
        });
    } else if (error.value.message == 'Order not found.') {
        noData.value = true;
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'invalid link, order not found.',
        });
    }
})

watch(questions, (newQuestions) => {
    if (newQuestions.length) {
        answers.value = newQuestions.map((q) => ({
            question_id: q.id,
            type: q.type,
            answer: "",
        }));
    }
});

watch(result, () => {
    if (!result.value?.allquestions) {
        noData.value = true;
    } else if (!result.value?.order) {
        noData.value = true;
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Order not found.'
        });
        answers.value = [];
        phone.value = '';
    } else {
        noData.value = false;
    }
})

const submit = async () => {
    try {
        const response = await client.mutate({
            mutation: gql`
                mutation CreateAnswer($phone: String, $answersQuestions: [AnswerInput!]!, $order_id: ID!) {
                    createAnswer(phone: $phone, answersQuestions: $answersQuestions, order_id: $order_id) {
                        id
                        order_id
                        created_at
                        updated_at
                    }
                }
            `,
            variables: {
                phone: phone.value || order.phone,
                order_id: order.value.id,
                answersQuestions: answers.value,
            },
        });
        ifAnwerExist.value = true;
        phone.value = '';
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
