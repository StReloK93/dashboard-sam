<template>
   <section @mousedown="$emit('close')"
      class="fixed inset-0 bg-zinc-950/70 backdrop-blur-[2px] flex justify-center items-center z-50">
      <aside @mousedown.stop class="w-[1366px] h-[600px] relative">
         <main class="slider-item px-1">
            <div
               class="bg-red-600 text-center mb-1.5 py-1 flex items-center justify-center rounded shadow font-semibold text-xl">
               Avtoag'dargichlarni texnik ko'rikdan o'tish jadvali
            </div>
            <section class="!overflow-y-auto scroll red-scroll w-full flex-grow">
               <main class="pb-2 flex justify-between">
                  <aside>
                     <VueDatePicker @update:model-value="handleDate" v-model="dates" range :format="formatDate" auto-apply
                        placeholder="Kunni tanlang" class="pr-4" />
                  </aside>
                  <aside>
                     <button @click="generatePDF"
                        class="px-4 h-9 content-center font-semibold rounded shadow bg-gray-600 active:bg-gray-400">
                        Yuklash <i class="fa-duotone fa-file-excel ml-2"></i>
                     </button>
                  </aside>
               </main>
               <main ref="importable">
                  <table class="w-full leading-3">
                     <tr class="border-b-2 border-zinc-900 bg-stone-950 ">
                        <th class="h-9"></th>
                        <th v-for="day in days" class="border-x-2 border-zinc-900 text-center text-xs">
                           {{ moment(day).format('DD-MM-YYYY') }}
                        </th>
                     </tr>
                     <tr v-for="toName in toList" class="bg-zinc-800 border-y-2 border-zinc-900 hover:bg-stone-950 group">
                        <td class="bg-stone-950 text-center h-12">{{ getToName(toName) }}</td>
   
                        <td v-for="day in days" class="border-x-2 border-zinc-900 group-hover:bg-zinc-700 px-1 content-start max-w-32"
                           :class="[{ 'bg-zinc-700': day == moment().format('YYYY-MM-DD') }]">
                           <span v-for="car in getGarageNumbers(day, toName, false)"
                              :class="moment(day) < moment().startOf('day') ? 'bg-red-900': 'bg-zinc-900'"
                              class="mr-1 mb-1 text-sm inline-block font-semibold px-1.5 rounded-sm neomorph">
                              {{ car.GarN }}
                              <tippy target="_parent">
                                 <div>Marka: <b>{{ car.MARKA_AC_NAME }}</b></div>
                                 <div>Turi: <b>{{ car.BASKET_NAME }}</b></div>
                              </tippy>
                           </span>
                           <span v-for="car in getGarageNumbers(day, toName, true)"
                              class="mr-1 mb-1 text-sm inline-block bg-green-900 font-semibold px-1.5 rounded-sm neomorph">
                              {{ car.GarN }}
                              <tippy target="_parent">
                                 <div>Marka: <b>{{ car.MARKA_AC_NAME }}</b></div>
                                 <div>Turi: <b>{{ car.BASKET_NAME }}</b></div>
                              </tippy>
                           </span>
                        </td>
                     </tr>
                  </table>
               </main>
            </section>
         </main>
      </aside>
   </section>
</template>

<script setup lang="ts">
import { formatDate } from '@/helpers/timeFormat'
import moment from 'moment'
import { getDaysArray, getToName } from '@/helpers/timeFormat'
import { ref, onMounted } from 'vue'
const importable = ref()




const toList = ref([])
const rows = ref([])
const days = ref([])

async function generatePDF() {
   axios.post('api/export-table-pdf', { html: importable.value.innerHTML }, {
      responseType: 'blob'
   }).then(response => {
      const url = window.URL.createObjectURL(new Blob([response.data]));
      const link = document.createElement('a');
      link.href = url;
      link.setAttribute('download', 'exported.pdf'); 
      document.body.appendChild(link);
      link.click();
      window.URL.revokeObjectURL(url);
      document.body.removeChild(link);
   })
}

const handleDate = (modelData) => {
   if (modelData) {
      dates.value = modelData
      getData()
   }
}
const startDates = new Date()
const endDates = new Date()
startDates.setDate(startDates.getDate() - 3);
endDates.setDate(endDates.getDate() + 7);

const dates = ref([startDates, endDates])




function getGarageNumbers(day, name, farq) {
   return rows.value.filter((row) => {
      const carDay = moment(row.Sana).format('YYYY-MM-DD')
      const selectedDay = moment(day).format('YYYY-MM-DD')
      return (carDay == selectedDay && (row.tos == name)) && ((row.Farq == null) == farq)
   })
}


function getData() {
   days.value = getDaysArray(dates.value[0], dates.value[1])

   axios.post('api/information/get-park-information', { startDate: days.value[0], endDate: days.value.at(-1) }).then(({ data: result }) => {
      result.forEach((car) => {
         if (car.TexOb) car.tos = car.TexOb
         if (car.TexOB) car.tos = car.TexOB
      })

      rows.value = result
      const texObsList = result.map((row) => row.tos)
      texObsList.sort((a, b) => +a.replace(/\D/g, "") - +b.replace(/\D/g, ""))

      toList.value = new Set(texObsList) as any
   })
}


onMounted(() => {
   getData()
})
</script>