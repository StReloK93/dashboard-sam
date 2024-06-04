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
                     <VueDatePicker @update:model-value="handleDate" v-model="dates" range :format="formatDate" auto-apply
                        placeholder="Kunni tanlang" />
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
import PricingChart from '@/config/PricingChart'
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


      const mileena = data.reduce((accum, item) => {
         const selected = accum.find((accumChild) => accumChild.name == item.smena && accumChild.smenaDate == item.smenaDate)

         if (selected) selected.difference += item.difference
         else accum.push({ name: item.smena, smenaDate: item.smenaDate, difference: item.difference })
         return accum
      }, [])

      const groupedData = {};

      mileena.forEach(item => {
         if (!groupedData[item.name]) groupedData[item.name] = { totalDifference: 0, count: 0 }
         groupedData[item.name].totalDifference += item.difference;
         groupedData[item.name].count += 1;
      });

      // Создаем массив с результатами и вычисляем среднее значение
      const chartData = Object.keys(groupedData).map(name => {
         const { totalDifference, count } = groupedData[name];
         return { name: name, y: totalDifference / count }
      });


      chartData.sort((a, b) => a.name.charCodeAt(0) - b.name.charCodeAt(0))
      // @ts-ignore
      Highcharts.chart(chart.value, PricingChart(chartData));
   })
}

onMounted(() => {
   getChartData()
})
</script>

