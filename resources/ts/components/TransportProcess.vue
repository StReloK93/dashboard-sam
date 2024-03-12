<template>
    <section :class="scrollColor" class="px-2">
        <ModeTitle :text="props.title"/>
        <TransitionGroup tag="main" name="fade" :class="props.gridCols" class="grid gap-1.5 py-3">
            <TransportButton 
                @click="$emit('openModal', transport)" 
                v-for="(transport, index) in props.data" 
                :name="transport.name" 
                :color="props.color"
                :type="transport.truck"
                :timer="transport[props.counter] ? transport[props.counter]: 0"
                :key="index"
                :timer_type="transport.timer_type"
            />
        </TransitionGroup>
    </section>
</template>

<script setup lang="ts">
import ModeTitle from '@/ui/ModeTitle.vue'
import TransportButton from '@/ui/TransportButton.vue'
const props = defineProps(['color', 'title', 'count', 'data', 'gridCols', 'counter', 'scrollColor'])
</script>