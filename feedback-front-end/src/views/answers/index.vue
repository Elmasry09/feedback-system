<template>
  <AuthenticatedLayout>
    <div class="relative overflow-x-auto">
      <table class="w-full table-fixed text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
          <tr>
            <th class="px-6 py-3 w-1/4">Order Name</th>
            <th class="px-6 py-3 w-1/4">Order Phone</th>
            <th class="px-6 py-3 w-1/4">Created at</th>
            <th class="px-6 py-3 w-1/4">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(answer, index) in answers" :key="index"
            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
              {{ answer.order.name }}
            </td>
            <td class="px-6 py-4">{{ answer.order.phone }}</td>
            <td class="px-6 py-4">
              {{ new Date(answer.created_at).toLocaleDateString() }}
            </td>
            <td class="px-6 py-4 space-x-2">
              <button @click="openModal(answer)" class="bg-blue-500 text-white px-3 py-1 rounded-md text-xs md:text-sm">
                View
              </button>
              <button @click="deleteAnswer(answer.id)"
                class="bg-red-500 text-white px-3 py-1 rounded-md text-xs md:text-sm">
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <AnswerModal :visible="showModal" :answer="selectedAnswer" @close="showModal = false" />


  </AuthenticatedLayout>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue'
import { initFlowbite } from 'flowbite'
import AuthenticatedLayout from '../Layouts/AuthenticatedLayout.vue'
import AnswerModal from '@/components/AnswerModal.vue'
import gql from 'graphql-tag'
import { useQuery, useApolloClient } from '@vue/apollo-composable'
import Swal from 'sweetalert2'
import { useRouter } from 'vue-router'
import { watch } from 'vue';


const { client } = useApolloClient()
const router = useRouter()
const showModal = ref(false)
const selectedAnswer = ref(null)

const openModal = (answer) => {
  selectedAnswer.value = answer
  showModal.value = true
}

const AnswersQuery = gql`
  query Answers {
    answers {
      id
      created_at
      updated_at
      order {
        name
        phone
      }
      answersQuestions {
        id
        text_answer
        question {
          text
        }
      }
    }
  }
`

const { result, loading, error, refetch } = useQuery(AnswersQuery)
const answers = computed(() => result.value?.answers || [])

watch(error, () => {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: error.value.message,
    });
})

const deleteAnswer = async (id) => {
  try {
    const response = await client.mutate({
      mutation: gql`
        mutation deleteAnswer($id: ID!) {
          deleteAnswer(id: $id) {
            message
          }
        }
      `,
      variables: { id }
    })
    await refetch();
    Swal.fire({
      icon: 'success',
      title: 'Deleted',
      text: 'Answer deleted successfully!',
    });

  } catch (err) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: `Failed to delete answer: ${err.message}`,
    });
  }
}

onMounted(() => {
  initFlowbite()
})
</script>
