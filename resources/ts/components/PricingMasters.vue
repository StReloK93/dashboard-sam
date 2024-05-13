<template>
   <section @mouseup="$emit('close')" class="fixed inset-0 bg-zinc-950/70 flex justify-center items-center z-50">
      <aside @mouseup.stop class="w-[992px] h-[540px] relative">
         <main class="slider-item py-1">
            <div class="px-1">
               <div :class="`bg-${props.color}-600`"
                  class="text-center h-9 mb-1.5 flex items-center justify-center rounded shadow font-semibold text-xl">
                  Avtoag'dargichlarni yoqilg'i olishda kutishga ketgan vaqtlari (Smenalar bo'yicha)
               </div>
               <div class="flex">
                  <span class="w-72 mb-1.5">
                     <VueDatePicker
                        @update:model-value="handleDate" 
                        v-model="dates"
                        range 
                        :format="formatDate"
                        auto-apply
                        placeholder="Kunni tanlang"
                     />
                  </span>
               </div>
            </div>
            <div ref="chart" class="flex-grow"></div>
         </main>
      </aside>
   </section>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import Highcharts from 'highcharts'
import PricingChart from '@/conf/PricingChart'
import { formatDate } from '@/helpers/timeFormat'
const handleDate = (modelData) => {
   if (modelData) {
      dates.value = modelData
      getChartData()
   }
}
const date = new Date()
date.setDate(date.getDate() - 7);

const dates = ref([
   date,
   new Date(),
])

const props = defineProps(['color'])
const chart = ref()

function getChartData() {
   axios.post('api/states/peresmena-graphic', {
      startDate: dates.value[0],
      endDate: dates.value[1],
   }).then(({ data }) => {
      const byGroup = data.reduce((accum, item) => {
         if (accum[item.smena]) accum[item.smena] += item.difference
         else accum[item.smena] = item.difference
         return accum
      }, {})

      
      
      
      const chartData = []
      for (const key in byGroup) {
         chartData.push({ name: key, y: byGroup[key] })
      }
      
      chartData.sort((a, b) => a.name.charCodeAt() - b.name.charCodeAt())

      console.log(chartData)
      
      // @ts-ignore
      Highcharts.chart(chart.value, PricingChart(chartData));
   })
}

onMounted(() => {
   getChartData()
})
</script>

