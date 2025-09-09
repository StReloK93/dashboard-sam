<template>
    <section :class="scrollColor" class="xl:px-2 px-1">
        <h3 class="text-zinc-500 uppercase text-center 2xl:text-base xl:text-sm  lg:text-xs text-[10px]">
            {{ props.title }}
        </h3>
        <TransitionGroup tag="main" name="fade" :class="props.gridCols" class="grid xl:gap-1.5 gap-1 py-1.5">

            <TransportButton
            v-for="(truck, index) in props.data"
                @click="$emit('openModal', truck)"
                :name="truck.transport.name"
                :color="props.color"
                :type="truck.transport"
                :timer="truck[props.counter] ? truck[props.counter] : 0" :timer_type="truck.timer_type"
                :reyslar="truck.reyslar" :key="index" />
            <TransportButton v-if="props.data?.length == 0" :color="props.color" />
        </TransitionGroup>
    </section>
</template>

<script setup lang="ts">
import TransportButton from '@/ui/TransportButton.vue'
const props = defineProps(['color', 'title', 'count', 'data', 'gridCols', 'counter', 'scrollColor'])
</script>