<template>
  <div
    class="rounded-2xl border border-gray-200 bg-white px-5 pb-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-6">
    <div class="flex flex-col gap-5 mb-6 sm:flex-row sm:justify-between">
      <div class="w-full">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Statistics</h3>
        <p class="mt-1 text-gray-500 text-theme-sm dark:text-gray-400">
          Percentage of orders that have an answer
        </p>
      </div>

      <!-- <div class="relative">
        <div class="inline-flex items-center gap-0.5 rounded-lg bg-gray-100 p-0.5 dark:bg-gray-900">
          <button
            v-for="option in options"
            :key="option.value"
            @click="selected = option.value"
            :class="[
              selected === option.value
                ? 'shadow-theme-xs text-gray-900 dark:text-white bg-white dark:bg-gray-800'
                : 'text-gray-500 dark:text-gray-400',
              'px-3 py-2 font-medium rounded-md text-theme-sm hover:text-gray-900 hover:shadow-theme-xs dark:hover:bg-gray-800 dark:hover:text-white',
            ]"
          >
            {{ option.label }}
          </button>
        </div>
      </div> -->
    </div>
    <div class="max-w-full overflow-x-auto custom-scrollbar">
      <div id="chartThree" class="-ml-4 min-w-[1000px] xl:min-w-full pl-2">
        <VueApexCharts v-if="!loading" type="" height="310" :options="chartOptions" :series="series" />
        <loadingComponent v-if="loading" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue'
import { useQuery } from '@vue/apollo-composable'
import gql from 'graphql-tag'
import loadingComponent from './loading.vue'
import Swal from 'sweetalert2'

const series = ref([]);

const statisticsQuery = gql`
  query {
    ordersStatistics {
      ordersHasAnswer
      ordersDosentHaveAnswer
    }
  }
`;

const { result, loading, error } = useQuery(statisticsQuery);

watch(result, () => {
  if (result.value && result.value.ordersStatistics) {
    series.value = [
      result.value.ordersStatistics.ordersHasAnswer,
      result.value.ordersStatistics.ordersDosentHaveAnswer
    ]
  }
});

watch(error, () => {
  Swal.fire({
    icon: 'error',
    title: 'Statistics Error',
    text: error.message
  });
})

const chartOptions = {
  chart: {
    width: 380,
    type: 'donut'
  },
  responsive: [{
    breakpoint: 480,
    options: {
      chart: {
        width: 200
      }
    }
  }],
};


</script>

<style scoped>
.area-chart {
  width: 100%;
}
</style>
