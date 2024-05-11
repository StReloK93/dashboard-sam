<template>
	<main class="h-56" ref="chart"></main>
</template>

<script setup lang="ts">
import { onMounted, ref, watch } from 'vue'
import Highcharts from 'highcharts'
import { Transports } from '@/entities/transports'
const props = defineProps(['chartname', 'startcolor', 'endcolor', 'process'])
const chart = ref()
const chartConstructor = ref()
const state = Transports()
const gaugeOptions: any = {
	accessibility: { enabled: false },
	chart: {
		type: 'solidgauge',
		spacing: [-20, -100, -20, -100],
		backgroundColor: 'transparent'
	},
	title: null,
	pane: {
		size: '100%',
		center: ['50%', '60%'],
		startAngle: -115,
		endAngle: 115,
		background: {
			borderWidth: 20,
			shape: 'arc',
			borderColor: '#313c69',
			outerRadius: '90%',
			innerRadius: '90%',
		}
	},
	yAxis: {
		min: 0,
		max: 100,
		minColor: '#009CE8',
		maxColor: '#009CE8',
		lineWidth: 0,
		tickWidth: 0,
		minorTickLength: 0,
		minTickInterval: 500,
		labels: {
			enabled: false
		},
		stops: [
			[0.5, {
				linearGradient: {
					x1: 0,
					x2: 0,
					y1: 0,
					y2: 1
				},
				stops: [
					[0, '#0000ff'],
					[1, '#e3e3f4']
				]
			}]
		],
	},

	tooltip: {
		enabled: false
	},
	plotOptions: {
		solidgauge: {
			borderColor: {
				linearGradient: {
					x1: 1,
					x2: 0,
					y1: 0,
					y2: 1
				},
				stops: [
					[0, props.startcolor],
					[1, props.endcolor],
				]
			},
			borderWidth: 20,
			radius: 90,
			innerRadius: '90%',
			dataLabels: {
				y: 5,
				borderWidth: 0,
				useHTML: true
			},
		},

	},
	series: [{
		name: 'windSpeed',
		data: [state[props.process].prosent],
		dataLabels: {
			y: -50,
			format: `
			<div class="gradient-text" style="text-align:center">
				<span style="font-size:34px">{y}%</span><br>
				<span style="font-size:15px">${props.chartname}</span><br>
				<span style="font-size:15px">${state[props.process].current} / ${67} </span>
         </div>`,
		}
	}],
}


watch(() => state[props.process].prosent, (current) => {
	chartConstructor.value.series[0].update({
		dataLabels: {
			format: `
			<div class="gradient-text" style="text-align:center">
					<span style="font-size:34px">{y}%</span><br>
					<span style="font-size:15px" class="mb-2">${props.chartname}</span><br>
					<span style="font-size:16px">${state[props.process].current} | ${67}</span>
			</div>`
		}
	});
	chartConstructor.value.series[0].setData([current]);
})

onMounted(() => {

	chartConstructor.value = Highcharts.chart(chart.value, gaugeOptions)
	const svg = chart.value.getElementsByTagName('svg');
	if (svg.length > 0) {
		var path = svg[0].getElementsByTagName('path');
		if (path.length > 1) {
			path[0].setAttributeNS(null, 'stroke-linejoin', 'round');
			path[1].setAttributeNS(null, 'stroke-linejoin', 'round');
		}
	}
})
</script>