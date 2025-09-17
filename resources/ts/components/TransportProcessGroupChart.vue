<template>
   <section ref="chartTimeLine" class="h-full !overflow-y-auto scroll indigo-scroll !overflow-hidden"></section>
</template>

<script setup lang="ts">
import { transportsTimeLine } from '@/config/charts'
import { ref, onMounted, watch } from 'vue'
import Highcharts from 'highcharts'
import { UTCTime } from '@/helpers/timeFormat'
const chartTimeLine = ref()
const props = defineProps(['selectedCars', 'dataSmena'])


watch(() => props.selectedCars,() => {
   updateChart()
})

function updateChart() {
   const sett = new Set(props.selectedCars.map((car) => car.transport.name))
   const carNames = [...sett]


   const chartData = props.selectedCars.map((car) => {
      return {
         start: UTCTime(car.geozone_in),
         end: UTCTime(car.geozone_out),
         y: carNames.findIndex((search) => search == car.transport.name),
         carName: car.name,
      }
   })


   const option: any = transportsTimeLine(chartData, carNames, props.dataSmena)
   Highcharts.ganttChart(chartTimeLine.value, option)
}


onMounted(() => {
   updateChart()
})
</script>