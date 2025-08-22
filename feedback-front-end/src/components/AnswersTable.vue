<template>
    <div
        class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-4 pb-3 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6">
        <div class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Recent Answers</h3>
            </div>
            <div class="flex items-center gap-3">
                <button @click="emit('refresh')"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                    <span class="hidden sm:inline">Refresh</span>
                </button>
                <router-link v-if="seeAll" :to="{ name: 'answers' }">
                    <button
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                        See all
                    </button>
                </router-link>
            </div>
        </div>
        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="w-full table-fixed text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <!-- Table Head -->
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th v-for="col in dynamicColumns" :key="col.key" class="px-6 py-3"
                            :style="{ width: (100 / dynamicColumns.length) + '%' }">
                            {{ col.label }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(answer, index) in answers" :key="index"
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">

                        <td v-for="col in dynamicColumns" :key="col.key" class="px-6 py-4">

                            <span v-if="col.key === 'name'">
                                {{ answer.order.name }}
                            </span>

                            <span v-if="col.key === 'created_at'">
                                {{ new Date(answer.created_at).toLocaleDateString() }}
                            </span>

                            <span v-if="col.key == 'phone'">
                                {{ answer.phone }}
                            </span>

                            <span v-else-if="col.key === 'actions'">
                                <button @click="openModal(answer)"
                                    class="bg-blue-500 text-white px-3 mr-2 py-1 rounded-md text-xs md:text-sm">
                                    view
                                </button>
                                <button @click="deleteAnswer(answer.id)"
                                    class="bg-red-500 text-white px-3 py-1 rounded-md text-xs md:text-sm">
                                    Delete
                                </button>
                            </span>
                        </td>

                    </tr>
                </tbody>
            </table>
            <noContent v-if="!answers.length" word="answers" />
            <AnswerModal :visible="showModal" :answer="selectedAnswer" @close="showModal = false" />
        </div>
    </div>
</template>
<script setup>
import { ref, computed } from 'vue'
import { useApolloClient } from '@vue/apollo-composable'
import Swal from 'sweetalert2'
import gql from 'graphql-tag'
import { RouterLink } from 'vue-router'
import noContent from '@/components/noContent.vue';
import AnswerModal from './AnswerModal.vue'
const { client } = useApolloClient()

const showModal = ref(false)
const selectedAnswer = ref(null)

const openModal = (answer) => {
    selectedAnswer.value = answer
    showModal.value = true
}

const props = defineProps({
    answers: { type: Array },
    columns: {
        type: Array,
        default: () => [
            { key: 'name', label: 'Customer Name' },
            { key: 'phone', label: 'Customer Phone' },
            { key: 'created_at', label: 'Created at' },
            { key: 'actions', label: 'Actions' }
        ]
    },
    seeAll: {
        type: Boolean,
        default: false
    }
})
const dynamicColumns = computed(() => {
    return props.columns
})

const emit = defineEmits(['delete','refresh'])

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
        emit('delete');
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
</script>