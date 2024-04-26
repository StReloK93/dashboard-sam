<template>
	<section :class="props.scrollColor" class="px-2 overflow-y-auto h-full scroll">
		<TransitionGroup name="fade">
			<GroupModal v-if="filter" :filter="filter" @close="filter = null" :color="color" />
			<GroupTimeLine v-if="timeLine" @close="timeLine = null" :group="timeLine"/>
      </TransitionGroup>
		<ModeTitle :text="props.title" class="text-center mb-2" />
		<main v-for="(group, key) of data">
			<div class="text-gray-500 text-xs flex justify-between items-center" :key="key">
				<button @click="timeLine = key" class="capitalize">
					<span :class="`bg-${color}-400 bg-${color}-500`" class="w-6 h-6 mr-0.5 text-base justify-center inline-flex items-center text-zinc-950 rounded-sm">
						<i class="fa-solid fa-watch-smart"></i>
					</span>
					<span :class="`text-${color}-500 active:bg-${color}-500`">
						{{ replace(key) }}
					</span>
				</button>
				<div class="flex items-center">
					<button @click="filter = key" :class="`text-${color}-500 active:bg-${color}-500`" class="overflow-hidden w-16 h-6 flex justify-end items-center">
						{{ minuteFormat(group.summTime) }}
						<span :class="`bg-${color}-400 bg-${color}-500`" class="ml-1.5 font-bold h-full w-6 justify-center inline-flex items-center text-zinc-950 rounded-sm">
							{{ group.counter }}
						</span>
					</button>
				</div>
			</div>
			<TransitionGroup tag="main" name="fade" :class="props.gridCols" class="grid gap-1.5 py-3">
				<TransportButton 
					@click="$emit('openModal', transport)" 
					v-for="(transport, index) in group.cars"
					:name="transport.name" 
					:color="props.color" 
					:type="transport.truck"
					:timer="transport.timer ? transport.timer: 0" 
					:timer_type="transport.timer_type" 
					:key="index"
				/>
				<TransportButton
					v-if="group.cars.length == 0"
					:color="props.color" 
				/>
			</TransitionGroup>
		</main>
	</section>
</template>

<script setup lang="ts">
import ModeTitle from '@/ui/ModeTitle.vue'
import TransportButton from '@/ui/TransportButton.vue'
import { minuteFormat } from '@/helpers/timeFormat'
import GroupModal from './GroupModal.vue'
import GroupTimeLine from './GroupTimeLine.vue'
import { ref } from 'vue'
const props = defineProps(['color', 'title', 'count', 'data', 'gridCols', 'scrollColor'])

const filter = ref(null)
const timeLine = ref(null)

function replace(str) {
	return str.replace('Пересменка', '').replace('Заправочный', '').replace('РВ', '')
}
</script>