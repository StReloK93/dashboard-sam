<template>
   <section @mousedown="$emit('close')" class="fixed inset-0 bg-zinc-950/70 flex justify-center items-center z-50">
      <aside @mousedown.stop class="w-[1100px] h-[540px] relative">
         <div class="py-2 slider-item">
            {{ dateTime }}
            <input type="datetime-local" v-model="dateTime">
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
import { timeDiff, UTCTime } from '@/helpers/timeFormat'
import { transportsTimeLine } from '@/conf/charts'
import moment from 'moment'
const dateTime = ref(moment().format('YYYY-MM-DD HH:mm') )

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
      startTime: car.geozone_in,
      endTime: car.geozone_out,
      y: transports.value?.findIndex((search) => search == car.name),
      carName: car.name,
      string: new Date(UTCTime(car.geozone_in)).toLocaleString()
   }
}))




onMounted(() => {
   axios.post(`api/smena-info`, { date: moment(dateTime.value).format('YYYY-MM-DD HH:mm') }).then(({ data: smena }) => {

      console.log(transports.value);
      
      const option: any = transportsTimeLine(chartData.value, transports.value, smena)
      Highcharts.ganttChart(chartTimeLine.value, option)
   })

})
</script>

