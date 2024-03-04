<template>
	<section :class="props.scrollColor" class="px-2 overflow-y-auto h-full scroll">
		<ModeTitle :text="props.title" class="text-center mb-2" />
		<main v-for="(group, key) of data">
			<div class="text-gray-500 text-xs flex justify-between items-center" :key="key">
				<span>
					{{ key }}
				</span>
				<span :class="`border-${color}-500 text-${color}-500`" class="border-2 w-12 h-6 flex justify-center items-center  rounded-full">
					{{ minuteFormat(group.summTime) }}
				</span>
			</div>
			<TransitionGroup tag="main" name="fade" :class="props.gridCols" class="grid gap-1.5 py-3">
				<TransportButton 
					@click="$emit('openModal', transport)" 
					v-for="(transport, index) in group.cars"
					:name="transport.name" 
					:color="props.color" 
					:timer="transport.timer ? transport.timer: 0" 
					:timer_type="transport.timer_type" 
					:key="index"
				/>
			</TransitionGroup>
		</main>
	</section>
</template>

<script setup lang="ts">
import ModeTitle from '@/ui/ModeTitle.vue'
import TransportButton from '@/ui/TransportButton.vue'
import { minuteFormat } from '@/helpers/timeFormat'
const props = defineProps(['color', 'title', 'count', 'data', 'gridCols', 'scrollColor'])
</script>