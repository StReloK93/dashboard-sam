<template>
   <section @mousedown="$emit('close')" class="fixed inset-0 bg-zinc-950/70 flex justify-center items-center z-50">
      <aside @mousedown.stop class="w-[850px] h-[540px] relative">
         <div class="p-2 slider-item">
            <!-- <div v-for="car in filtered">
               {{ car }} {{ timeDiff(car, 'second') }}
            </div> -->
            <section ref="chartTimeLine" class="h-full"></section>
         </div>
      </aside>
   </section>
</template>

<script setup lang="ts">
const props = defineProps(['group'])
import { ref, onMounted } from 'vue'
import Highcharts from 'highcharts'
import { Transports } from '@/entities/transports/Transports'
import { computed } from 'vue'
import { timeDiff } from '@/helpers/timeFormat'

const chartTimeLine = ref()
const store = Transports()
const filtered = computed(() => store.inOilConflict.filter((car) => car.geozone == props.group))

const transports = computed(() => { 
   const array = filtered.value.map((car) => car.name)
   const sett = new Set(array)
   return [...sett]
})


const chartData = computed(() => filtered.value.map((car) => {
   return {
      start: UTCTime(car.geozone_in),
      end: UTCTime(car.geozone_out),
      y: transports.value?.findIndex((search) => search == car.name),
   }
}))

function UTCTime<Number>(time: string) {
   const date = new Date(time)
   return Date.UTC(date.getFullYear(), date.getMonth(), date.getUTCDate(), date.getHours(), date.getUTCMinutes() )
}

const option: any = {
   series: [{
      data: chartData.value
   }],
   accessibility: { enabled: false },
   chart: { backgroundColor: 'transparent', plotBorderWidth: 1, plotBorderColor:'#333' },
   title: { text: props.group },
   subtitle: { text: null },
   xAxis: [
      {
         type: 'datetime',
         min: UTCTime('2024-04-26 09:00'),
         max: UTCTime('2024-04-26 22:00'),
         opposite: false,
         grid: {
            borderWidth: 0
         },
         gridLineColor: '#333',
         gridLineWidth: 1,
      },
      {
         opposite: false,
         labels: {
            enabled: false,
         },
         grid: {
            borderWidth: 0
         },
         gridLineColor: '#111',
         gridLineWidth: 1,
      }
   ],
   tooltip: { enabled: true },
   yAxis: {
      categories: transports.value,
      grid: {
         borderWidth: 0,
         enabled: true,
         cellHeight: 10
      },
      gridLineWidth: 0,
      staticScale: 25
   },
   rangeSelector: {
      allButtonsEnabled: true
   },
   plotOptions: {
      series: {
         borderColor: null,
         borderRadius: "0%",
         connectors: {
            dashStyle: "ShortDot",
            lineWidth: 2,
            radius: 1,
            startMarker: {
               enabled: false,
            },
         },
         pointPadding: 0.09,
         groupPadding: 0,
         plotBorderWidth: 0,
      },
   },

}

onMounted(() => {
   Highcharts.ganttChart(chartTimeLine.value, option);
})
</script>

