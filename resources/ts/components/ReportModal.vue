<template>
   <section @mouseup="$emit('close')" class="fixed inset-0 bg-zinc-950/70 flex justify-center items-center z-50">
      <aside @mouseup.stop class="w-[992px] h-[540px] relative">
         <main class="slider-item py-1">
            <div class="px-1">
               <div
                  class="text-center bg-gray-600 h-9 mb-1.5 flex items-center justify-center rounded shadow font-semibold text-xl">
                  Avtoag'dargichlarni ishga tayyorligi bo'yicha hisobot
               </div>
               <div class="flex">
                  <span class="w-72 mb-1.5">
                     <VueDatePicker @update:model-value="handleDate" v-model="dates" :format="formatDate" auto-apply
                        placeholder="Kunni tanlang" />
                  </span>
               </div>
            </div>
            <div class="flex-grow !overflow-y-auto scroll indigo-scroll !overflow-hidden px-2">
               <table class="w-full">
                  <tr class="border-b-4 border-zinc-900 bg-stone-950 sticky top-0">
                     <td class="py-1 pl-2">Transport</td>
                     <td class="py-1">Umumiy soat</td>
                     <td class="py-1">Ishladi</td>
                     <td class="py-1">Umumiy tamirlashda</td>

                     <td class="py-1">Mayda tamirlashda</td>
                     <td class="py-1">ATBda</td>
                     <td class="py-1">MTBF</td>
                     <td class="py-1">MTBR</td>
                  </tr>
                  <tr v-for="item in tableData" class="bg-zinc-800 border-y-4 border-zinc-900">
                     <td class="py-1 pl-2">{{ item.name }}</td>
                     <td class="py-1">{{ item.kv }} Soat</td>
                     <td class="py-1">{{ item.kg }} Soat</td>
                     <td class="py-1">{{ item.vr }} Soat</td>
                     <td class="py-1"> {{ item.sum_vr_p }} Soat <span class="text-xs text-gray-400">({{ item.kol_p }} marta)</span></td>
                     <td class="py-1">{{ item.sum_vr_uat }} Soat <span class="text-xs text-gray-400">({{ item.kol_uat }} marta)</span></td>
                     <td class="py-1">{{ item.mtbf }}</td>
                     <td class="py-1">{{ item.mtbr }}</td>


                  </tr>
               </table>

            </div>
         </main>
      </aside>
   </section>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { formatDate } from '@/helpers/timeFormat'
const handleDate = (modelData) => {
   if (modelData) {
      dates.value = modelData
      getChartData()
   }
}

const dates = ref(new Date())
const tableData = ref(null)
function getChartData() {
   axios.post('api/transports/car_reports', { date: dates.value, }).then(({ data }) => {
      tableData.value = data.filter((item) => item.name.includes('MAN') == false)
   })
}

// mtbf
// mtbr

onMounted(() => getChartData())
</script>

