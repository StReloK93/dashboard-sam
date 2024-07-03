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
               </main>
               <table class="w-full">
                  <tr class="border-b-2 border-zinc-900 bg-stone-950 ">
                     <td class="h-9"></td>
                     <td v-for="day in days" class="border-x-2 border-zinc-900 text-center">{{ day }}</td>
                     <td class="text-center"></td>
                  </tr>
                  <tr v-for="toName in toList" class="bg-zinc-800 border-y-2 border-zinc-900 hover:bg-stone-950 group">
                     <td class="bg-stone-950 text-center h-12">{{ getToName(toName) }}</td>

                     <td v-for="day in days" class="border-x-2 border-zinc-900 group-hover:bg-zinc-700 px-1 content-start"
                        :class="[{ 'bg-zinc-700': day == moment().format('YYYY-MM-DD') }]">

                        <span v-for="car in getGarageNumbers(day, toName)"
                           class="mr-1 text-sm bg-zinc-900 font-semibold px-1.5 rounded-sm neomorph">
                           {{ car.GarN }}
                           <tippy target="_parent">
                              <div>Marka: <b>{{ car.MARKA_AC_NAME }}</b></div>
                              <div>Turi: <b>{{ car.BASKET_NAME }}</b></div>
                              <div>{{car.NameTO}}: <b>{{ car.DataTO }}</b></div>
                           </tippy>
                        </span>
                        <span v-for="car in getFacts(day, toName)"
                           class="mr-1 text-sm bg-green-900 font-semibold px-1.5 rounded-sm neomorph">
                           {{ car.GarN }}
                        </span>
                        
                     </td>
                     <td class="border-x-2 border-zinc-900 group-hover:bg-zinc-700 !bg-red-900 px-1 content-start">
                        <span v-for="car in getOtherTos(toName)"
                           class="mr-1 text-sm bg-red-600 font-semibold px-1.5 rounded-sm shadow-md inline-block content-center">
                           {{ car.GarN }} <span class="text-red-950 font-bold inline-block ml-1">{{ car.Farq }}</span>
                        </span>
                     </td>
                  </tr>
               </table>
            </section>
         </main>
      </aside>
   </section>
</template>

<script setup lang="ts">
import { formatDate } from '@/helpers/timeFormat'
import moment from 'moment'
import { getDaysArray } from '@/helpers/timeFormat'
import { ref, onMounted } from 'vue'

const tab = ref(1)

const toList = ref([])
const rows = ref([])
const days = ref([])
const otherDays = ref([])


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

const dates = ref([startDates,endDates])


function getToName(name: any) {
   const index = name?.replace(/\D/g, "")
   const arrayNames = [
      'TO-250 (1)',
      'TO-500 (2)',
      'TO-250 (3)',
      'TO-1000 (4)',
      'TO-250 (5)',
      'TO-500 (6)',
      'TO-250 (7)',
      'TO-2000 (8)',
      'TO-250 (9)',
      'TO-500 (10)',

      'TO-250 (11)',
      'TO-3000 (12)',
      'TO-250 (13)',
      'TO-500 (14)',
      'TO-250 (15)',
      'TO-4000 (16)',
   ]

   return arrayNames[+index - 1]
}



function getGarageNumbers(day, name) {
   return rows.value.filter((row) => row.Sana == day && row.TexOb == name)
}


function getFacts(day, name) {
   const index = name?.replace(/\D/g, "")
   
   return rows.value.filter((row) => {
      console.log(row.DataTO, day);
      return row.DataTO == day && row.idVidTO == index
   })
}


function getOtherTos(toName) {
   return otherDays.value.filter((row) => row.TexOb == toName)
}


function getData() {
   days.value = getDaysArray(dates.value[0], dates.value[1])

   axios.post('api/information/get-park-information', { startDate: days.value[0], endDate: days.value.at(-1) }).then(({ data: result }) => {
      rows.value = result

      otherDays.value = result.filter((row) => {
         const isTrue = moment(row.Sana).isBetween(moment(days.value[0]), moment(days.value.at(-1)).add(1, 'days'))
         return isTrue == false
      })

      const texObsList = result.map((row) => row.TexOb)
      texObsList.sort((a, b) => +a.replace(/\D/g, "") - +b.replace(/\D/g, ""))

      toList.value = new Set(texObsList) as any
   })
}


onMounted(() => {
   getData()
})
</script>