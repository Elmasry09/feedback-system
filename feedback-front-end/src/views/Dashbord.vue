<template>
  <AuthenticatedLayout>
    <div class="grid grid-cols-12 gap-4 md:gap-6">
      <div class="col-span-12 space-y-6 xl:col-span-7">
        <ecommerce-metrics :orders="{ count: 555 }" :answers="{ count: 2222 }" />
        <monthly-sale />
      </div>
      <div class="col-span-12 xl:col-span-5">
        <answers-table :answers="answers" :see-all="true" @delete="handleDeleteAnswer" @refresh="refetch" :columns="[
          { key: 'name', label: 'Customer Name' },
          { key: 'actions', label: 'Actions' }
        ]" />
        <loadingComponent v-if="loading" />
      </div>

      <div class="col-span-12">
        <statistics-chart />
      </div>

      <div class="col-span-12 xl:col-span-5">
        <QuestionsTable :questions="questions" @delete="handleDeleteQuestion" @refresh="refetch" :columns="[
          { key: 'question', label: 'Question' },
          { key: 'actions', label: 'Actions' }
        ]" />
        <loadingComponent v-if="loading" />
      </div>

      <div class="col-span-12 xl:col-span-7">
        <orders-table :orders="orders" :columns="[
          { key: 'name', label: 'Customer Name' },
          { key: 'phone', label: 'Phone Number' },
          { key: 'actions', label: 'Actions' }
        ]" :see-all="true" @delete="handleDeleteOrder" @refresh="refetch" @edit="handleUpdateOrder" />
        <loadingComponent v-if="loading" />
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import EcommerceMetrics from '../components/EcommerceMetrics.vue'
import MonthlySale from '../components/MonthlySale.vue'
import StatisticsChart from '../components/StatisticsChart.vue'
import AuthenticatedLayout from './Layouts/AuthenticatedLayout.vue'
import OrdersTable from '@/components/OrdersTable.vue'
import AnswersTable from '@/components/AnswersTable.vue'
import QuestionsTable from '@/components/QuestionsTable.vue'
import loadingComponent from '@/components/loading.vue'
import gql from 'graphql-tag';
import { useQuery } from '@vue/apollo-composable';
import { computed, watch } from 'vue';

const Querys = gql`
query Orders {
    orders (first: 5) {
      data {
        id
        name
        phone
        product_name
        created_at
    }
    }
    answers (first: 5) {
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
    }
    questions (first: 5) {
        data {
        id
        text
        type
        choices
        created_at
        }
    }
}`;
const { result, loading, error, refetch } = useQuery(Querys);
const orders = computed(() => result.value?.orders?.data || []);
const answers = computed(() => result.value?.answers?.data || []);
const questions = computed(() => result.value?.questions?.data || []);

watch(error, () => {
  Swal.fire({
    icon: 'error',
    title: 'Error',
    text: error.value.message,
  });
});

const handleUpdateOrder = () => {
  refetch();
};

const handleDeleteOrder = () => {
  refetch();
};

const handleDeleteAnswer = () => {
  refetch();
};
const handleDeleteQuestion = () => {
  refetch();
};

</script>
