<template>
    <aside :class="props.textColor"
        class=" w-[90px] h-[90px] bg-zinc-900 neomorph rounded-full border-2 border-transparent relative flex justify-center items-center flex-col">
        <slot></slot>
        <span v-if="props.type != 'time'" class="font-semibold">
            {{ props.summa }}
        </span>
        <span v-else>
            {{ minuteFormat(props.summa) }}
        </span>
        <CircleTimer :timer="props.timer" :radius="44" @start="$emit('start')" :color="props.bgColor" class="transition-all duration-1000 absolute top-[-3px] left-[-3px]" />
    </aside>
</template>

<script setup lang="ts">
import CircleTimer from '@/ui/CircleTimer.vue'
import { minuteFormat } from '@/helpers/timeFormat'
const props = defineProps(['summa', 'bgColor', 'textColor', 'timer', 'type'])

</script>