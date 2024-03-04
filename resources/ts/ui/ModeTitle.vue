<template>
    <h3 class="text-zinc-500 uppercase">
        {{ text }}
    </h3>
</template>

<script setup lang="ts">
const { text } = defineProps(['text'])
</script>