<template>
    <aside :class="textColor"
        class=" w-[90px] h-[90px] bg-zinc-900 neomorph rounded-full border-2 border-transparent relative flex justify-center items-center flex-col">
        <slot></slot>
        <span v-if="type != 'time'" class="font-semibold">
            {{ summa }}
        </span>
        <span v-else>
            {{ minuteFormat(summa) }}
        </span>
        <CircleTimer :timer="timer" :radius="44" @start="$emit('start')" :color="bgColor" class="transition-all duration-1000 absolute top-[-3px] left-[-3px]" />
    </aside>
</template>

<script setup lang="ts">
import CircleTimer from '@/ui/CircleTimer.vue'
import { minuteFormat } from '@/helpers/timeFormat'
const { summa, bgColor, textColor, timer, type } = defineProps(['summa', 'bgColor', 'textColor', 'timer', 'type'])

</script>