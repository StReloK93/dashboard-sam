<template>
   <section @mousedown="$emit('close')"
      class="fixed inset-0 bg-zinc-950/70 backdrop-blur-[2px] flex justify-center items-center z-50">
      <aside @mousedown.stop class="2xl:w-[1366px] xl:w-[1200px] w-[700px] h-[600px] relative">
         <main class="slider-item px-1">
            <div
               class="bg-red-600 text-center mb-1.5 py-1 flex items-center justify-center rounded shadow font-semibold xl:text-xl text-sm">
               Ekskavatorlarni texnik ko'rikdan o'tish jadvali
            </div>
            <section class="!overflow-y-auto scroll red-scroll w-full flex-grow">
               <main class="pb-2 flex justify-between">
                  <aside>
                     <input type="month" @change="getData" v-model="dates" class="text-gray-700 px-2 py-1 rounded shadow outline-none">
                  </aside>
                  <aside>
                     <button @click="generatePDF"
                        class="px-4 h-9 content-center font-semibold rounded shadow bg-gray-600 active:bg-gray-400">
                        Yuklash <i class="fa-duotone fa-file-excel ml-2"></i>
                     </button>
                  </aside>
               </main>
               <main ref="importable">
                  <table class="w-full leading-3 lg:text-base text-xs">
                     <tr class="border-b-2 border-zinc-900 bg-stone-950 ">
                        <th class="h-9 xl:max-w-20 max-w-10"></th>
                        <th v-for="(day, index) in days" class="border-x-2 w-10 border-zinc-900 text-center xl:text-xs text-[10px]">
                           {{ index + 1 }}
                        </th>
                     </tr>
                     <tr v-for="toName in toList"
                        class="bg-zinc-800 border-y-2 border-zinc-900 hover:bg-stone-950 group">
                        <td class="bg-stone-950 text-center h-10 max-w-20">{{ toName }}</td>
                        <td v-for="(day, index) in days" class="border-x-2 border-zinc-900 group-hover:bg-zinc-700 content-start text-center py-1"
                           :class="[{ 'bg-gray-500': index + 1 == moment().date() }]">
                           <div 
                              v-for="excavator in getGarageNumbers(day, toName)"
                              :class="[
                                 {'bg-teal-600': excavator.isDone == '1' },
                                 {'bg-red-600': excavator.isDone == '0'},
                                 {'bg-zinc-900': excavator.isDone == null},
                              ]"
                              class="mb-1 w-8 py-1 rounded-sm mx-auto last:mb-0 font-semibold text-sm"
                           >
                              {{ excavator.n_garaj }}
                              <tippy  target="_parent">
                                 <div>{{ excavator.comments }}</div>
                                 <div class="font-semibold">{{ excavator.model }}</div>
                              </tippy>
                           </div>
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
import moment from 'moment'
import { getDaysInMonth, extractYearAndMonth } from '@/helpers/timeFormat'
import { ref, onMounted } from 'vue'
const importable = ref()

const toList = ref([])
const days = ref([])
const rows = ref([])

const dates = ref(moment().format('YYYY-MM'))

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

function getGarageNumbers(day, name) {
   const filtered =  rows.value.filter((row) => {
      
      const selectedDay = moment(day).format('YYYY-MM-DD')

      return (row.date_of == selectedDay && (row.causeType == name))
   })
   filtered.sort((a, b) => +a.n_garaj - +b.n_garaj)

   return filtered
}



async function getData() {
   toList.value = []
   days.value = []
   rows.value = []
   const { year, month } = extractYearAndMonth(dates.value)
   days.value = getDaysInMonth(+year, +month - 1)

   const { data: result } = await axios.post('api/get-to-excavators', { year, month })

   rows.value = result
   const causes = result.map((cause) => cause.causeType)
   toList.value = new Set(causes) as any

}


onMounted(() => {
   getData()
})
</script>