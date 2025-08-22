<template>
  <div class="join flex justify-center my-4" v-if="paginator">
    <button class="join-item btn"
            :disabled="currentPage === 1"
            @click="$emit('change', currentPage - 1)">
      «
    </button>

    <button class="join-item btn">
      Page {{ paginator.currentPage }} / {{ paginator.lastPage }}
    </button>

    <button class="join-item btn"
            :disabled="!paginator.hasMorePages"
            @click="$emit('change', currentPage + 1)">
      »
    </button>
  </div>
</template>

<script setup>
defineProps({
  paginator: {
    type: Object,
    required: true
  },
  currentPage: {
    type: Number,
    required: true
  }
});

defineEmits(['change']);
</script>
