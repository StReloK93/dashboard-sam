<template>
	<section :class="props.scrollColor" class="xl:px-2 px-1 overflow-y-auto scroll relative">
		<TransitionGroup name="fade">
			<TransportProcessGroupModal :color="props.color" v-if="timeLine && color != 'gray'" @close="timeLine = null" :group="timeLine" />
		</TransitionGroup>
		<h3 class="text-zinc-500 uppercase text-center mb-2 sticky 2xl:text-base xl:text-sm  lg:text-xs text-[10px] top-0">
			{{ props.title }}
		</h3>
		<main v-for="(group, key) of data">
			<button @click="timeLine = key" class="text-gray-500 xl:text-xs text-[10px] flex lg:flex-row flex-col justify-between lg:items-center items-start capitalize w-full hover:opacity-70 active:opacity-90"
				:key="key">
				<span :class="`text-${color}-500 active:bg-${color}-500`">
					{{ replace(key) }}
				</span>
				<div v-if="color != 'gray'" :class="`text-${color}-500`" class="flex items-center">
					{{ minuteFormat(group.summTime) }}
					<span :class="`bg-${color}-400 bg-${color}-500`"
						class="xl:w-11 lg:w-9 w-8 lg:h-6 h-5 ml-1 text-base justify-between xl:px-1.5 px-1 inline-flex items-center text-zinc-950 rounded-sm">
						<span class="xl:text-sm lg:text-xs text-[10px] font-semibold">
							{{ group.counter }}
						</span>
						<i class="fa-solid fa-watch-smart"></i>
					</span>
				</div>
			</button>
			<TransitionGroup tag="main" name="fade" :class="props.gridCols" class="grid gap-1.5 py-3">
				<TransportButton @click="$emit('openModal', transport)" v-for="(transport, index) in group.cars"
					:name="transport.name" :color="props.color" :type="transport.truck" :reyslar="transport.reyslar"
					:timer="transport.timer ? transport.timer : 0" :timer_type="transport.timer_type" :key="index" />
				<TransportButton v-if="group.cars.length == 0" :color="props.color" />
			</TransitionGroup>
		</main>
	</section>
</template>

<script setup lang="ts">
import TransportButton from '@/ui/TransportButton.vue'
import { minuteFormat } from '@/helpers/timeFormat'
import TransportProcessGroupModal from './TransportProcessGroupModal.vue'
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