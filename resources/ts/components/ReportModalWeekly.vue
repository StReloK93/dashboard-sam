<template>
   <section  class="flex-grow !overflow-y-auto scroll indigo-scroll relative">
      <aside v-if="loader == false">
         <div class="flex justify-between items-stretch px-1 sticky top-0 bg-zinc-900">
            <span class="w-72 mb-1.5">
               <VueDatePicker @update:model-value="handleDate" v-model="dates" :format="formatDate" auto-apply
                  placeholder="Kunni tanlang" />
            </span>
            <a :href="`/api/export/report/${moment(dates).format('YYYY-MM-DD')}/1`"
               class="px-4 h-9 content-center font-semibold rounded shadow bg-indigo-600 active:bg-indigo-400">
               Yuklash <i class="fa-duotone fa-file-excel ml-2"></i>
            </a>
         </div>
         <div class=" px-1">
            <table ref="table" class="w-full">
               <tr class="border-b-4 border-zinc-900 bg-zinc-900 sticky top-10">
                  <td class="py-1 pl-2">Transport</td>
                  <td class="py-1">Umumiy soat</td>
                  <td class="py-1">Ishladi <span class="text-xs text-gray-400">(Soat)</span> </td>
                  <td class="py-1">Umumiy tamirlashda <span class="text-xs text-gray-400">(Soat)</span></td>
                  <td class="py-1">Mayda tamirlashda <span class="text-xs text-gray-400">(Soat)</span></td>
                  <td class="py-1">ATBda <span class="text-xs text-gray-400">(Soat)</span></td>
                  <td class="py-1">MTBF</td>
                  <td class="py-1">MTBR</td>
               </tr>
               <tr v-for="item in tableData" class="bg-zinc-800 border-y-4 border-zinc-900">
                  <td class="py-1 pl-2">{{ item.name }}</td>
                  <td class="py-1">{{ item.kv }}</td>
                  <td class="py-1">{{ item.kg }}</td>
                  <td class="py-1">{{ item.vr }}</td>
                  <td class="py-1">
                     {{ item.sum_vr_p }}
                     <span class="text-xs text-gray-400">
                        ({{ item.kol_p }} marta)
                     </span>
                  </td>
                  <td class="py-1">
                     {{ item.sum_vr_uat }}
                     <span class="text-xs text-gray-400">
                        ({{ item.kol_uat }} marta)
                     </span>
                  </td>
                  <td class="py-1">{{ item.mtbf }}</td>
                  <td class="py-1">{{ item.mtbr }}</td>
               </tr>
            </table>
         </div>
      </aside>
      <PreLoader v-else />
   </section>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { formatDate } from '@/helpers/timeFormat'
import moment from 'moment'

const handleDate = (modelData) => {
   if (modelData) {
      dates.value = modelData
      getChartData()
   }
}

const table = ref()
const loader = ref(true)


const dates = ref(new Date())
const tableData = ref(null)
function getChartData() {
   axios.post('api/transports/car_reports', { date: dates.value, weekCount: 1 }).then(({ data }) => {
      tableData.value = data
      loader.value = false
   }).catch(() => loader.value = false)
}

onMounted(() => getChartData())
</script>