<template>
    <aside :class="props.textColor"
        class="2xl:w-[90px] 2xl:h-[90px] xl:w-[78px] xl:h-[78px] lg:w-[64px] lg:h-[64px] w-[58px] h-[58px] bg-zinc-900 text-base neomorph rounded-full border-2 border-transparent relative flex justify-center items-center flex-col">
        <slot></slot>
        <span v-if="props.type != 'time'" class="font-semibold xl:text-base lg:text-xs text-[10px]">
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

if (window.innerWidth >= 1560) {
    radius.value = 44
}
else if (window.innerWidth >= 1440) {
    radius.value = 38
}
else if (window.innerWidth >= 1024) {
    radius.value = 30
}
else{
    radius.value = 28
}



// 78/38
// 90/44
</script>