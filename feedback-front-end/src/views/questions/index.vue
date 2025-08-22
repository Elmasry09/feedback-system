<template>
    <AuthenticatedLayout>
        <div class="relative overflow-x-auto">
            <QuestionsTable :questions="questions" @refresh="refetch" @delete="handleDelete" @edit="openModal" />
            <Pagination v-if="paginator" :paginator="paginator" :currentPage="currentPage" @change="changePage" />
            <loadingComponent v-if="loading" />
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '../Layouts/AuthenticatedLayout.vue';
import { initFlowbite } from 'flowbite';
import gql from 'graphql-tag';
import { useQuery } from '@vue/apollo-composable'
import Swal from 'sweetalert2';
import { useRouter } from 'vue-router';
import { watch, ref, onMounted, computed } from 'vue';
import loadingComponent from '@/components/loading.vue';
import QuestionsTable from '@/components/QuestionsTable.vue';
import Pagination from '@/components/Pagination .vue';

const questionsQuery = gql`
query  questions($page: Int) {
    questions (first: 15, page: $page) {
        data {
            id
            text
            type
            choices
            created_at
        }
        paginatorInfo {
            currentPage
            lastPage
            hasMorePages
        }
    }
}`;

const handleDelete = () => {
    refetch();
}

const router = useRouter();
const currentPage = ref(1);
const { result, loading, error, refetch } = useQuery(questionsQuery, { page: currentPage.value });
const questions = computed(() => result.value?.questions?.data || []);
const paginator = computed(() => result.value?.questions?.paginatorInfo);

const changePage = (page) => {
    if (page >= 1 && page <= paginator.value.lastPage) {
        currentPage.value = page;
        refetch({ page: currentPage.value });
    }
};
watch(error, () => {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: error.value.message
    });

})

onMounted(() => {
    if (router.options.history.state.refresh) {
        refetch();
        router.options.history.state.refresh = false;
    }
    initFlowbite();
})

</script>
