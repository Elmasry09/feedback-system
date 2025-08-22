<template>
  <AuthenticatedLayout>
    <div class="relative overflow-x-auto">
      <answers-table :answers="answers" @refresh="refetch" @delete="handleDelete" />
      <Pagination v-if="paginator" :paginator="paginator" :currentPage="currentPage" @change="changePage" />
      <loadingComponent v-if="loading" />
    </div>

    <AnswerModal :visible="showModal" :answer="selectedAnswer" @close="showModal = false" />

  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '../Layouts/AuthenticatedLayout.vue'
import loadingComponent from '@/components/loading.vue';
import AnswersTable from '@/components/AnswersTable.vue'
import Pagination from '@/components/Pagination .vue';
import { onMounted, ref, computed, watch } from 'vue'
import { initFlowbite } from 'flowbite'
import AnswerModal from '@/components/AnswerModal.vue'
import gql from 'graphql-tag'
import { useQuery } from '@vue/apollo-composable'
import Swal from 'sweetalert2'

const AnswersQuery = gql`
  query Answers ($page: Int) {
    answers (first: 15, page: $page) {
      data {
        id
      phone
      created_at
      order {
        name
      }
      answersQuestions {
        id
        text_answer
        question {
          text
        }
      }
      }
      paginatorInfo {
            currentPage
            lastPage
            hasMorePages
        }
    }
  }
`

const currentPage = ref(1);
const { result, loading, error, refetch } = useQuery(AnswersQuery, { page: currentPage.value })
const answers = computed(() => result.value?.answers?.data || [])
const paginator = computed(() => result.value?.answers?.paginatorInfo);

const changePage = (page) => {
  if (page >= 1 && page <= paginator.value.lastPage) {
    currentPage.value = page;
    refetch({ page: currentPage.value });
  }
};
const handleDelete = () => {
  refetch()
}

watch(error, () => {
  Swal.fire({
    icon: 'error',
    title: 'Error',
    text: error.value.message,
  });
})


onMounted(() => {
  initFlowbite()
})
</script>
