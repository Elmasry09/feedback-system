<template>
    <div
        class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-4 pb-3 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6">
        <div class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Recent Orders</h3>
            </div>
            <div class="flex items-center gap-3">
                <button @click="emit('refresh')"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                    <span class="hidden sm:inline">Refresh</span>
                </button>
                <router-link :to="{ name: 'add-order' }">
                    <button
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                        <span class="hidden sm:inline">Add Order</span>
                    </button>
                </router-link>
                <router-link v-if="seeAll" :to="{ name: 'orders' }">
                    <button
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                        See all
                    </button>
                </router-link>
            </div>
        </div>
        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="w-full table-fixed text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th v-for="col in dynamicColumns" :key="col.key" class="px-6 py-3"
                            :style="{ width: (100 / dynamicColumns.length) + '%' }">
                            {{ col.label }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(order, index) in orders" :key="index"
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">

                        <td v-for="col in dynamicColumns" :key="col.key" class="px-6 py-4">

                            <span v-if="col.key === 'created_at'">
                                {{ new Date(order[col.key]).toLocaleDateString() }}
                            </span>

                            <span v-else-if="col.key !== 'actions'">
                                {{ order[col.key] }}
                            </span>

                            <span v-else-if="col.key === 'actions'">
                                <button @click="openModal(order)"
                                    class="bg-blue-500 text-white px-3 mr-2 py-1 rounded-md text-xs md:text-sm">
                                    Edit
                                </button>
                                <button @click="deleteOrder(order.id)"
                                    class="bg-red-500 text-white px-3 py-1 rounded-md text-xs md:text-sm">
                                    Delete
                                </button>
                            </span>
                        </td>

                    </tr>
                </tbody>
            </table>
            <noContent v-if="!orders.length" word="orders" />
            <edit :visible="showModal" :order="selectedOrder" @updated="handleOrderUpdate" @close="showModal = false" />
        </div>
    </div>
</template>

<script setup>
import edit from '@/views/orders/edit.vue'
import noContent from '@/components/noContent.vue';
import { ref, computed } from 'vue'
import { useApolloClient } from '@vue/apollo-composable'
import Swal from 'sweetalert2'
import gql from 'graphql-tag'
import { RouterLink } from 'vue-router'

const { client } = useApolloClient()

const props = defineProps({
    orders: { type: Array },
    columns: {
        type: Array,
        default: () => [
            { key: 'name', label: 'Customer Name' },
            { key: 'phone', label: 'Phone' },
            { key: 'created_at', label: 'Created at' },
            { key: 'actions', label: 'Actions' }
        ]
    },
    seeAll: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['delete', 'edit', 'refresh'])

const showModal = ref(false)
const selectedOrder = ref(null)
const openModal = (order) => {
    selectedOrder.value = order
    showModal.value = true
}
const handleOrderUpdate = () => {
    showModal.value = false
    emit('edit')
}

const deleteOrder = async (id) => {
    try {
        await client.mutate({
            mutation: gql`
        mutation deleteOrder($id: ID!) {
          deleteOrder(id: $id) { message }
        }
      `,
            variables: { id }
        })
        Swal.fire({ icon: 'success', title: 'Success', text: 'Order deleted successfully!' })
        emit('delete')
    } catch (err) {
        Swal.fire({ icon: 'error', title: 'Error', text: `Failed to delete order: ${err.message}` })
    }
}

const dynamicColumns = computed(() => {
    return props.columns
})
</script>
