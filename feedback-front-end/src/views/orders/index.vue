<template>
    <AuthenticatedLayout>
        <div class="relative overflow-x-auto">
            <div class="flex justify-end mb-4">
                <button @click="sendMessages"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                    Send Messages
                </button>
            </div>
            <orders-table :orders="orders" @delete="handleDelete" @edit="handleUpdateOrder" />
            <Pagination v-if="paginator" :paginator="paginator" :currentPage="currentPage" @change="changePage" />
            <loadingComponent v-if="loading" />
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '../Layouts/AuthenticatedLayout.vue';
import Pagination from '@/components/Pagination .vue';
import OrdersTable from '@/components/OrdersTable.vue';
import { initFlowbite } from 'flowbite';
import gql from 'graphql-tag';
import { useQuery } from '@vue/apollo-composable';
import Swal from 'sweetalert2';
import { useRouter } from 'vue-router';
import { onMounted, watch, computed, ref } from 'vue';
import loadingComponent from '@/components/loading.vue';
import apiClient from '@/axios/api';

const OrdersQuery = gql`
query Orders($page: Int) {
    orders (first: 15, page: $page) {
        data {
            id
            name
            phone
            product_name
            created_at
        }
        paginatorInfo {
            currentPage
            lastPage
            hasMorePages
        }
    }
}`;


const handleUpdateOrder = () => {
    refetch();
};

const handleDelete = () => {
    refetch();
};

const sendMessages = () => {
    apiClient.post("/run-send-messages")
        .then((response) => {
            Swal.fire({
                icon: "success",
                title: "Success",
                text: response.data.message,
            });
        })
        .catch((error) => {
            Swal.fire({
                icon: "error",
                title: "Oops.. failed to send messages",
                text: error.response?.data?.message || "Something went wrong",
            });
        });
};


const router = useRouter();
const currentPage = ref(1);
const { result, loading, error, refetch } = useQuery(OrdersQuery, { page: currentPage.value });
const orders = computed(() => result.value?.orders?.data || []);
const paginator = computed(() => result.value?.orders?.paginatorInfo);

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
        text: error.value.message,
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
