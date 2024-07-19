<template>
   <section @mouseup="$emit('close')" class="fixed inset-0 bg-zinc-950/70 flex justify-center items-center z-50">
      <aside @mouseup.stop class="w-[992px] h-[540px] relative">
         <main class="slider-item py-1">
            <div class="px-1">
               <div :class="`bg-${props.color}-600`"
                  class="text-center h-9 mb-1.5 flex items-center justify-center rounded shadow font-semibold text-xl">
                  Avtoag'dargichlarni yoqilg'i olishda kutishga ketgan vaqtlari (Smenalar bo'yicha)
               </div>
               <div class="flex justify-between">
                  <span class="w-72 mb-1.5">
                     <VueDatePicker @update:model-value="changeDate" v-model="dates" range :format="formatDate" auto-apply
                        placeholder="Kunni tanlang" />
                  </span>
                  <button @click="exportExcel" class="px-4 h-9 content-center font-semibold rounded shadow bg-gray-600 active:bg-gray-400">
                     Yuklash <i class="fa-duotone fa-file-excel ml-2"></i>
                  </button>
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
import { formatDate, calculateChartDataPrices, downloadExcel, formatterToExcel } from '@/helpers/timeFormat'
function changeDate(modelData) {
   if (modelData) {
      dates.value = modelData
      getChartData()
   }
}
const date = new Date()
date.setDate(date.getDate() - 7);

const dates = ref([date, new Date()])

const props = defineProps(['color'])
const chart = ref()
const allData = ref([])
function getChartData() {
   axios.post('api/states/peresmena-graphic', {
      startDate: dates.value[0],
      endDate: dates.value[1],
   }).then(({ data }) => {
      allData.value = data.allData

      const chartData = calculateChartDataPrices(data.chartData)
      // @ts-ignore
      Highcharts.chart(chart.value, PricingChart(chartData));
   })
}


function exportExcel() {
   const totalArray = formatterToExcel(allData.value)
   downloadExcel(totalArray, 'download.xls')
}

onMounted(() => {
   getChartData()
})
</script>

