<template>
   <section @mousedown="$emit('close')"
      class="fixed inset-0 bg-zinc-950/70 backdrop-blur-[2px] flex justify-center items-center z-50">
      <aside @mousedown.stop class="w-[1366px] h-[600px] relative">
         <main class="slider-item px-1">
            <div
               class="bg-red-600 text-center mb-1.5 py-1 flex items-center justify-center rounded shadow font-semibold text-xl">
               Ekskavatorlarni texnik ko'rikdan o'tish jadvali
            </div>
            <section class="!overflow-y-auto scroll red-scroll w-full flex-grow">
               <main class="pb-2 flex justify-between">
                  <aside>
                     <input type="month" @change="getData" v-model="dates" class="text-gray-700 px-2 py-1 rounded shadow outline-none">
                     <!-- <VueDatePicker @update:model-value="handleDate" v-model="dates" range :format="formatDate" auto-apply
                        placeholder="Kunni tanlang" class="pr-4" /> -->
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
                        <th v-for="(day, index) in days" class="border-x-2 w-10 border-zinc-900 text-center text-xs">
                           {{ index + 1 }}
                        </th>
                     </tr>
                     <tr v-for="toName in toList"
                        class="bg-zinc-800 border-y-2 border-zinc-900 hover:bg-stone-950 group">
                        <td class="bg-stone-950 text-center h-10">EKG {{ toName.n_garaj }}</td>
                        <td v-for="(day, index) in days"
                           class="border-x-2 border-zinc-900 group-hover:bg-zinc-700 content-center text-center"
                           :class="[{ 'bg-zinc-700': day == moment().format('YYYY-MM-DD') }]">
                           {{ toName[index + 1] }}
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

async function getData() {
   toList.value = []
   days.value = []
   const { year, month } = extractYearAndMonth(dates.value)
   days.value = getDaysInMonth(+year, +month - 1)
   const { data: result } = await axios.post('api/get-to-excavators', { year, month })
   result.sort((a, b) => +a.n_garaj.replace(/\D/g, "") - +b.n_garaj.replace(/\D/g, ""))
   toList.value = result
}


onMounted(() => {
   getData()
})
</script>