<template>
	<button :class="{ 'opacity-0 cursor-default': props.name == null }"
		class="inline-flex justify-between w-24 items-center px-2 py-1 neomorph rounded-2xl bg-zinc-800 active:shadow-md hover:bg-zinc-900 transition-all relative">
		<div :class="[buttonColor.text, { 'opacity-0': props.name == null }]"
			class="mr-3 font-semibold text-sm leading-[10px] text-left">
			<span v-if="props.color == 'sky'">ГМ{{ props.name?.replace(/\D/g, "") }}</span>
			<span v-else>C{{ props.name?.replace(/\D/g, "") }}</span>
			<br>
			<span class="text-gray-500 text-[10px] leading-[0px] pl-0.5">
				{{ props.type?.type }}
			</span>
		</div>
		<template v-if="props.timer_type">
			<span :class="colorLine" class="absolute w-2 h-2 right-4 -top-px z-10"></span>
			<span :class="colorLine" class="absolute w-2 h-2 right-4 -bottom-px z-10"></span>
		</template>
		<time :class="buttonColor.bg"
			class="w-6 h-6 inline-flex text-xs text-zinc-900 rounded-full font-bold justify-center items-center z-20 relative">
			{{ props.timer }}
		</time>
	</button>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const colors = {
	sky: {
		bg: 'bg-sky-400',
		text: 'text-sky-400',
		border: 'border-sky-400',
	},
	green: {
		bg: 'bg-green-500',
		text: 'text-green-400',
		border: 'border-green-400',
	},
	red: {
		bg: 'bg-red-500',
		text: 'text-red-500',
		border: 'border-red-500',
	},
	yellow: {
		bg: 'bg-yellow-500',
		text: 'text-yellow-500',
		border: 'border-yellow-500',
	},
	orange: {
		bg: 'bg-orange-500',
		text: 'text-orange-500',
		border: 'border-orange-500',
	},
	gray: {
		bg: 'bg-gray-500',
		text: 'text-gray-500',
		border: 'border-gray-500',
	},
	indigo: {
		bg: 'bg-indigo-400',
		text: 'text-indigo-400',
		border: 'border-indigo-500',
	},
}

const props = defineProps({
	timer: { type: Number },
	color: {
		required: true, type: String,
	},
	name: { type: String },
	timer_type: { type: Number },
	type: { type: Object },
})


const colorLine = computed(() => props.timer_type == 2 ? 'bg-gray-400' : 'bg-yellow-400')


const buttonColor = computed(() => colors[props.color])
</script>