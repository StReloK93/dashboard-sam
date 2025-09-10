<template>
    <aside :class="props.textColor"
        class="xl:w-[70px] xl:h-[70px] w-[56px] h-[56px] bg-zinc-900 text-base neomorph rounded-full border-2 border-transparent relative flex justify-center items-center flex-col">
        <slot></slot>
        <span v-if="props.type != 'time'" class="font-semibold text-xs">
            {{ props.summa }}
        </span>
        <span v-else class="font-semibold xl:text-base text-xs">
            {{ minuteFormat(props.summa) }}
        </span>
        <template v-if="radius">
            <CircleTimer :timer="props.timer" :radius="radius" @start="$emit('start')" :color="props.bgColor"
                class="transition-all duration-1000 absolute top-[-3px] left-[-3px]" />
        </template>
    </aside>
</template>

<script setup lang="ts">
import CircleTimer from '@/ui/CircleTimer.vue'
import { minuteFormat } from '@/helpers/timeFormat'
import { ref } from 'vue';
const props = defineProps(['summa', 'bgColor', 'textColor', 'timer', 'type'])

const radius = ref(null)


if (window.innerWidth >= 1440) {
    radius.value = 34
}

else{
    radius.value = 27
}



// 78/38
// 90/44
</script>