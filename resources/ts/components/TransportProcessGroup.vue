<template>
	<section :class="props.scrollColor" class="px-2 overflow-y-auto scroll">
		<TransitionGroup name="fade">
			<GroupTimeLine :color="props.color" v-if="timeLine" @close="timeLine = null" :group="timeLine" />
		</TransitionGroup>
		<h3 class="text-zinc-500 uppercase text-center mb-2">
			{{ props.title }}
		</h3>
		<main v-for="(group, key) of data">
			<button @click="timeLine = key" class="text-gray-500 text-xs flex justify-between items-center capitalize w-full hover:opacity-70 active:opacity-90"
				:key="key">
				<span :class="`text-${color}-500 active:bg-${color}-500`">
					{{ replace(key) }}
				</span>
				<div :class="`text-${color}-500`" class="flex items-center">
					{{ minuteFormat(group.summTime) }}
					<span :class="`bg-${color}-400 bg-${color}-500`"
						class="w-11 h-6 ml-1 text-base justify-between px-1.5 inline-flex items-center text-zinc-950 rounded-sm">
						<span class="text-sm font-semibold">
							{{ group.counter }}
						</span>
						<i class="fa-solid fa-watch-smart"></i>
					</span>
				</div>
			</button>
			<TransitionGroup tag="main" name="fade" :class="props.gridCols" class="grid gap-1.5 py-3">
				<TransportButton @click="$emit('openModal', transport)" v-for="(transport, index) in group.cars"
					:name="transport.name" :color="props.color" :type="transport.truck"
					:timer="transport.timer ? transport.timer : 0" :timer_type="transport.timer_type" :key="index" />
				<TransportButton v-if="group.cars.length == 0" :color="props.color" />
			</TransitionGroup>
		</main>
	</section>
</template>

<script setup lang="ts">
import TransportButton from '@/ui/TransportButton.vue'
import { minuteFormat } from '@/helpers/timeFormat'
import GroupTimeLine from './GroupTimeLine.vue'
import { ref, watch } from 'vue'
const props = defineProps(['color', 'title', 'count', 'data', 'gridCols', 'scrollColor'])

const timeLine = ref(null)

function replace(str) {
	return str.replace('Пересменка', '').replace('Заправочный', '').replace('РВ', '')
}


watch(() => props.data, () => {
	for (const key in props.data) {
		if (props.color == 'orange' && props.data[key].cars.length > 1) {
			let mySound = new Audio('/sound/signal.mp3')
			mySound.play()
		}
	}
})
</script>