<template>
  <div v-if="visible" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="relative w-full max-w-6xl max-h-screen p-4">
      <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700 flex flex-col h-full">
        <div
          class="flex items-center justify-between p-4 md:p-5 border-b border-gray-200 dark:border-gray-600 rounded-t">
          <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
            Answer Details
          </h3>
          <button @click="$emit('close')" type="button"
            class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
            <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M1 1l6 6m0 0l6 6M7 7l6-6M7 7L1 13" />
            </svg>
          </button>
        </div>
        <div class="p-4 md:p-5 space-y-4 overflow-y-auto max-h-[70vh]">
          <p class="text-base text-gray-800 dark:text-white">
            <strong>Order Name:</strong> {{ answer?.order?.name || '—' }}
          </p>
          <p class="text-base text-gray-800 dark:text-white">
            <strong>Order Phone:</strong> {{ answer?.phone || '—' }}
          </p>
          <p class="text-base text-gray-800 dark:text-white">
            <strong>Created at</strong> {{ formattedDate }}
          </p>
          <div class="dark:bg-gray-800 h-screen">
            <div class="w-full max-w-3xl px-2 mx-auto py-12 dark:bg-transparent dark:text-gray-200">
              <h3 class="mt-3 text-xl font-bold text-gray-800 md:text-2xl dark:text-gray-100">
                Answers Questions
              </h3>
              <div class="grid max-w-5xl mx-auto mt-6 divide-y divide-gray-200 dark:divide-gray-700">
                <details v-for="answerQuestion in answer.answersQuestions" :key="answerQuestion.id" class="group py-4">
                  <summary class="flex items-center justify-between font-medium list-none cursor-pointer">
                    <span>{{ answerQuestion.question.text }}</span>
                    <span class="transition group-open:rotate-180">
                      <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor"
                        stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"
                        class="dark:stroke-gray-400">
                        <path d="M6 9l6 6 6-6"></path>
                      </svg>
                    </span>
                  </summary>
                  <p class="mt-3 text-gray-600 group-open:animate-fadeIn dark:text-gray-300">
                    {{ answerQuestion.text_answer }}
                  </p>
                </details>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  visible: Boolean,
  answer: Object
})

const emit = defineEmits(['close'])

const formattedDate = computed(() => {
  return props.answer?.created_at
    ? new Date(props.answer.created_at).toLocaleDateString()
    : '—'
})

console.log(props.answer)
</script>
