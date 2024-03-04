<template>
    <button class="inline-flex justify-between w-24 items-center px-2 py-1 neomorph rounded-2xl bg-zinc-800 active:shadow-md hover:bg-zinc-900 transition-all relative">
        <span :class="buttonColor.text" class="mr-3 font-semibold text-sm">{{ props.name }}</span>
        <template v-if="props.timer_type">
            <span :class="colorLine" class="absolute w-2 h-2 right-4 -top-px z-10"></span>
            <span :class="colorLine" class="absolute w-2 h-2 right-4 -bottom-px z-10"></span>
        </template>
        <time :class="buttonColor.bg"
            class="w-6 h-6 inline-flex text-xs text-zinc-900 rounded-full font-bold justify-center items-center z-20 relative">
            {{ props.timer }}
        </time>
    </button>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps({
    timer: { required: true, type: Number },
    color: { required: true, type: String },
    name: { required: true, type: String },
    timer_type: { type: Number },
})


const colorLine = computed(() => props.timer_type == 2 ? 'bg-gray-400' : 'bg-slate-950')

const buttonColor = computed(() => {
    const colors = {
        green: {
            bg: 'bg-green-500',
            text: 'text-green-500',
            border: 'border-green-500',
        },
        red: {
            bg: 'bg-red-500',
            text: 'text-red-500',
            border: 'border-red-500',
        },
        yellow: {
            bg: 'bg-yellow-500',
            text: 'text-yellow-500',
            border: 'border-yellow-500',
        },
        orange: {
            bg: 'bg-orange-500',
            text: 'text-orange-500',
            border: 'border-orange-500',
        },
        gray: {
            bg: 'bg-gray-500',
            text: 'text-gray-500',
            border: 'border-gray-500',
        },
        indigo: {
            bg: 'bg-indigo-400',
            text: 'text-indigo-400',
            border: 'border-indigo-500',
        },
    }
    return colors[props.color]
})

</script>