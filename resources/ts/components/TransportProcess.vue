<template>
    <section :class="scrollColor" class="px-2">
        <h3 class="text-zinc-500 uppercase text-center mb-2">
			{{ props.title }}
		</h3>
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
            <TransportButton v-if="props.data.length == 0" :color="props.color"/>
        </TransitionGroup>
    </section>
</template>

<script setup lang="ts">
import TransportButton from '@/ui/TransportButton.vue'
const props = defineProps(['color', 'title', 'count', 'data', 'gridCols', 'counter', 'scrollColor'])
</script>