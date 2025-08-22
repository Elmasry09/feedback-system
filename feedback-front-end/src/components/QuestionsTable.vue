<template>
    <div
        class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-4 pb-3 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6">
        <div class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-md font-semibold text-gray-800 dark:text-white/90">Recent Questions</h3>
            </div>
            <div class="flex items-center gap-3">
                <button @click="emit('refresh')"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                    <span class="hidden sm:inline">Refresh</span>
                </button>
                <router-link :to="{ name: 'add-question' }">
                    <button
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                        <span class="hidden sm:inline">Add Question</span>
                    </button>
                </router-link>
                <router-link v-if="seeAll" :to="{ name: 'questions' }">
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

                <!-- Table Body -->
                <tbody>
                    <tr v-for="(question, index) in questions" :key="index"
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">

                        <td v-for="col in dynamicColumns" :key="col.key" class="px-6 py-4">

                            <span v-if="col.key === 'created_at'">
                                {{ new Date(question.created_at).toLocaleDateString() }}
                            </span>

                            <span v-if="col.key == 'question'">
                                {{ question.text }}
                            </span>

                            <span v-if="col.key === 'actions'">
                                <button @click="openModal(question)"
                                    class="bg-blue-500 text-white px-3 mr-2 py-1 rounded-md text-xs md:text-sm">
                                    Edit
                                </button>
                                <button @click="deleteQuestion(question.id)"
                                    class="bg-red-500 text-white px-3 py-1 rounded-md text-xs md:text-sm">
                                    Delete
                                </button>
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
            <noContent v-if="!questions.length" word="questions" />
            <edit :visible="showModal" :question="selectedQuestion" @updated="handleUpdateQuestion" @close="showModal = false" />
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useApolloClient } from '@vue/apollo-composable'
import Swal from 'sweetalert2'
import gql from 'graphql-tag'
import edit from '@/views/questions/edit.vue'
import { RouterLink } from 'vue-router'
import noContent from '@/components/noContent.vue';

// Apollo Client
const { client } = useApolloClient()

// Props
const props = defineProps({
    questions: { type: Array },
    columns: {
        type: Array,
        default: () => [
            { key: 'question', label: 'Question' },
            { key: 'created_at', label: 'Created at' },
            { key: 'actions', label: 'Actions' }
        ]
    },
    seeAll: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['delete','refresh'])

// Modal state
const showModal = ref(false)
const selectedQuestion = ref(null)
const openModal = (order) => {
    selectedQuestion.value = order
    showModal.value = true
}
const handleUpdateQuestion = () => {
    showModal.value = false
    emit('edit')
}

const deleteQuestion = async (id) => {
    try {
        const response = await client.mutate({
            mutation: gql`
                mutation deleteQuestion($id: ID!) {
                    deleteQuestion(id: $id) {
                        message
                    }
                }
            `,
            variables: { id }
        });
        emit('delete');
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Question deleted successfully!',
        });
    } catch (err) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: `Failed to delete question: ${err.message}`,
        });
    }

};

const dynamicColumns = computed(() => {
    return props.columns
})
</script>
