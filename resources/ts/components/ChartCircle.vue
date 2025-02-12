<template>
	<main class="2xl:h-52 xl:h-40 h-32" ref="chart"></main>
</template>

<script setup lang="ts">
import { onMounted, ref, watch, Ref } from 'vue'
import Highcharts from 'highcharts'
import { useI18n } from 'vue-i18n'
const { t, locale } = useI18n()


const model: Ref<any> = defineModel()


const props = defineProps(['chartname', 'startcolor', 'endcolor', 'process'])
const chart = ref()
const chartConstructor = ref()
const gaugeOptions: any = {
	accessibility: { enabled: false },
	chart: {
		type: 'solidgauge',
		spacing: [-20, -100, -20, -100],
		backgroundColor: 'transparent'
	},
	title: null,
	pane: {
		size: '93%',
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
			borderWidth: 15,
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
		data: [model.value?.prosent],
		dataLabels: {
			y: -50,
			format: getText(),
		}
	}],
}

function getText() {
	return `<div class="gradient-text relative xl:top-0 top-2" style="text-align:center">
					<span class="xl:text-2xl text-[20px]">{y}%</span><br>
					<span class="mb-2 2xl:text-sm xl:text-xs text-[10px]">${t(props.chartname)}</span><br>
					<span class="2xl:text-base xl:text-sm  text-xs">${model.value?.current} / ${model.value?.max} </span>
					</div>`
}

watch(() => model.value?.prosent, (current) => {
	chartConstructor.value.series[0].update({
		dataLabels: {
			format: getText()
		}
	});
	chartConstructor.value.series[0].setData([current]);
})

watch(() => locale.value, () => {
	chartConstructor.value.series[0].update({
		dataLabels: {
			format: getText()
		}
	});
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