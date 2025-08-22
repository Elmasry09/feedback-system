<template>
  <div
    class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-6">
    <div class="flex items-center justify-between">
      <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Monthly Orders</h3>
    </div>

    <div class="max-w-full overflow-x-auto h-full custom-scrollbar mt-4">
      <div id="chartOne" class="-ml-5 min-w-[650px] xl:min-w-full pl-2">
        <VueApexCharts v-if="!loading" type="bar" height="200" :options="chartOptions" :series="series" />
      </div>
      <loadingComponent v-if="loading" />
    </div>
  </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue'
import { useQuery } from '@vue/apollo-composable'
import gql from 'graphql-tag'
import loadingComponent from './loading.vue'
import Swal from 'sweetalert2'
const GET_MONTHLY_ORDERS = gql`
  query {
    monthlyOrders {
      month
      total
    }
  }
`

const { result, loading, error } = useQuery(GET_MONTHLY_ORDERS);

const series = ref([{ name: 'Orders', data: [] }]);
const categories = ref([]);
watch(result, () => {
  if (result.value) {
    categories.value = result.value.monthlyOrders.map(o => o.month);
    series.value[0].data = result.value.monthlyOrders.map(o => o.total);
  }
})


watch(error, () => {
    Swal.fire({
      icon: 'error',
      title: 'Monthly Orders Error',
      text: error.message
    });
})

const chartOptions = computed(() => ({
  chart: {
    type: 'bar',
    fontFamily: 'Outfit, sans-serif',
    toolbar: { show: true }
  },
  xaxis: {
    categories: categories.value
  },
  plotOptions: {
    bar: {
      horizontal: false,
      columnWidth: '60%',
      borderRadius: 5,
      borderRadiusApplication: 'end'
    }
  },
  colors: ['#465fff'],
  dataLabels: { enabled: true },
  tooltip: {
    enabled: false,
  }
}))
</script>
