<template>
	<button :class="{ 'opacity-0 cursor-default': props.name == null }"
		class="inline-flex justify-between 2xl:w-24 xl:w-[72px] lg:w-[67px] w-[52px] items-center 2xl:px-1.5 xl:px-1 xl:py-0.5 2xl:py-1 px-1 py-0.5 neomorph rounded-2xl bg-zinc-800 active:shadow-md hover:bg-zinc-900 transition-all relative">
		<div :class="[getManName(props.name) ? 'text-sky-400' :`${buttonColor.text}` , { 'opacity-0': props.name == null }]"
			class="2xl:pl-1 xl:pl-0.5 font-semibold 2xl:text-[14px] xl:text-[11px] text-[10px]  leading-[10px] text-left">
			<span class="leading-none" v-if="props.color == 'sky'">ГМ{{ props.name?.replace(/\D/g, "") }}</span>
			<span class="leading-none" v-else-if="timePistali">{{ pistaliName(props.name) }}</span>
			<span class="leading-none" v-else>C{{ props.name?.replace(/\D/g, "") }}</span>
			<div class="text-gray-500 2xl:text-[10px] xl:text-[9px] text-[8px] leading-none">
				{{ props.type?.type }}
			</div>
		</div>
		<span v-if="props.timer_type" :class="colorLine" class="absolute lg:w-2 w-[6px] h-[calc(100%+1px)] 2xl:right-[14px] xl:right-[11px] right-[10px] -top-px z-10"></span>
		<time :class="[getManName(props.name) ? 'bg-sky-400' :`${buttonColor.bg}`]"
			class="2xl:w-6 2xl:h-6 xl:w-[22px] xl:h-[22px] lg:w-[20px] lg:h-[20px] w-[18px] h-[18px] inline-flex 2xl:text-xs xl:text-[11px] text-[10px] text-zinc-900 rounded-full font-bold justify-center items-center z-20">
			{{ props.timer }}
		</time>
	</button>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'

const timePistali = ref(settings.day_smena == "07:50")

function pistaliName(name){
	if(name?.toLowerCase().indexOf("man") > 0){
		return 'M'+name?.replace(/ /g, '').replace(/\(.*?\)/g, '').match(/№(\d+)/)[1]
	}
	else if(name) return 'C'+name?.replace(/ /g, '').replace(/\(.*?\)/g, '').match(/№(\d+)/)[1]
	else return ""
}
function getManName(name){
	return name?.toLowerCase().indexOf("man") > 0
}

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