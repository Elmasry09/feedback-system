<template>
    <div class="flex flex-col justify-center items-center mt-8">
        <img class="w-32 h-32"
            src="https://res.cloudinary.com/daqsjyrgg/image/upload/v1690261234/di7tvpnzsesyo7vvsrq4.svg"
            alt="image empty states">
        <p class="mt-4 text-gray-700 font-medium text-lg text-center">No {{ word }} available.</p>
    </div>
</template>

<script setup>

defineProps({
    word: String
})

</script>