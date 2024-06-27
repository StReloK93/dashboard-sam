<template>
   <section @mousedown="$emit('close')"
      class="fixed inset-0 bg-zinc-950/70 backdrop-blur-[2px] flex justify-center items-center z-50">
      <aside @mousedown.stop class="w-[1366px] h-[600px] relative">
         <main class="slider-item px-1">
            <div
               class="bg-red-600 text-center mb-1.5 py-1 flex items-center justify-center rounded shadow font-semibold text-xl">
               Avtoag'dargichlarni texnik korikdan o'tish jadvali
            </div>
            <section class="!overflow-y-auto scroll red-scroll w-full flex-grow">
               <main class="pb-2">
                  <input type="date" @change="getData" v-model="startDate"
                     class="mr-2 text-gray-800 px-1.5 py-0.5 rounded-sm">
                  <input type="date" @change="getData" v-model="endDate"
                     class="mr-2 text-gray-800 px-1.5 py-0.5 rounded-sm">
               </main>
               <table class="w-full">
                  <tr class="border-b-2 border-zinc-900 bg-stone-950 ">
                     <td class="h-9"></td>
                     <td v-for="day in days" class="border-x-2 border-zinc-900 text-center">{{ day }}</td>
                     <td class="text-center"></td>
                  </tr>
                  <tr v-for="toName in toList" class="bg-zinc-800 border-y-2 border-zinc-900 hover:bg-stone-950 group">
                     <td class="bg-stone-950 text-center h-12">{{ toName }}</td>

                     <td v-for="day in days" class="border-x-2 border-zinc-900 group-hover:bg-zinc-700 px-1 content-start"
                        :class="[{ 'bg-zinc-700': day == moment().format('YYYY-MM-DD') }]">

                        <span v-for="car in getGarageNumbers(day, toName)"
                           class="mr-1 text-sm bg-zinc-900 font-semibold px-1.5 rounded-sm neomorph">
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
import moment from 'moment'
import { getDaysArray } from '@/helpers/timeFormat'
import { ref, onMounted } from 'vue'
const startDate = ref(moment().add(-3, 'days').format('YYYY-MM-DD'))
const endDate = ref(moment().add(7, 'days').format('YYYY-MM-DD'))

const toList = ref([])
const rows = ref([])
const days = ref([])
const otherDays = ref([])





function getGarageNumbers(day, name) {
   return rows.value.filter((row) => row.Sana == day && row.TexOb == name)
}

function getOtherTos(toName) {
   return otherDays.value.filter((row) => row.TexOb == toName)
}


function getData() {
   days.value = getDaysArray(startDate.value, endDate.value)

   axios.post('api/information/get-park-information', { startDate: startDate.value, endDate: endDate.value, park: 1 }).then(({ data: result }) => {
      rows.value = result

      otherDays.value = result.filter((row) => {
         const isTrue = moment(row.Sana).isBetween(moment(startDate.value), moment(endDate.value).add(1, 'days'))
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