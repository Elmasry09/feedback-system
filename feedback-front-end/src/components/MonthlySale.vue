<template>
  <div
    class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-6">
    <div class="flex items-center justify-between">
      <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Monthly Orders</h3>
    </div>
    <div class="max-w-full overflow-x-auto h-full custom-scrollbar mt-4">
      <div id="chartOne" class="-ml-5 min-w-[650px] xl:min-w-full pl-2">
        <VueApexCharts type="bar" height="200" :options="chartOptions" :series="series" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  monthlyOrders: {
    type: Array,
    default: () => []
  }
});

const categories = computed(() =>
  props.monthlyOrders.map(o => o.month)
);

const series = computed(() => [
  { name: 'Orders', data: props.monthlyOrders.map(o => o.total) }
]);

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
