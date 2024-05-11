<template>
   <section @mousedown="$emit('close')" class="fixed inset-0 bg-zinc-950/70 flex justify-center items-center z-50">
      <aside @mousedown.stop class="w-[1100px] h-[540px]">
         <div class="slider-item">
            <div class="mb-1.5">
               <div :class="`bg-${props.color}-600`"
                  class="text-center mx-2 h-9 flex items-center justify-center rounded shadow font-semibold text-xl">
                  {{ props.group }}
               </div>
               <h3 class="flex gap-1 px-2 mt-1.5 rounded font-semibold h-9">
                  <div class="w-40">
                     <VueDatePicker @update:model-value="handleDate" v-model="pickers.date" :format="formatDate" auto-apply
                        placeholder="Kunni tanlang" />
                  </div>
                  <div class="flex gap-1 ml-1">
                     <button @click="changeSmena(1)" :class="setColor(pickers.smena == 1)" class="w-24 rounded shadow">
                        1 - Smena
                     </button>
                     <button @click="changeSmena(2)" :class="setColor(pickers.smena == 2)" class="w-24 rounded shadow">
                        2 - Smena
                     </button>
                  </div>
                  <div class="flex gap-1 ml-1 flex-grow">
                     <button @click="tab = 3" :class="setColor(tab == 3)" class="w-20 h-full font-semibold rounded shadow">
                        <i class="fa-solid fa-table-list"></i> Jadval
                     </button>
                     <button @click="tab = 1" :class="setColor(tab == 1)" class="w-20 h-full font-semibold rounded shadow">
                        <i class="fa-solid fa-chart-gantt"></i> Grafik
                     </button>
                     <button @click="tab = 2" v-if="props.color == 'orange'" :class="setColor(tab == 2)" class="px-2 h-full font-semibold rounded shadow ml-3 flex-grow">
                        <i class="fa-solid fa-table-list"></i> Yoqilg'i olishda kutib qolish  {{ secondsToFormatTime(fullWaitTime) }}
                     </button>
                  </div>
               </h3>
            </div>
            <section v-show="tab == 1" ref="chartTimeLine"
               class="h-full !overflow-y-auto scroll indigo-scroll !overflow-hidden"></section>
            <section v-show="tab == 2" class="h-full !overflow-y-auto scroll indigo-scroll !overflow-hidden px-2">
               <table class="w-full">
                  <tr class="border-b-4 border-zinc-900 bg-stone-950">
                     <td class="py-1 rounded-tl px-2">№</td>
                     <td class="py-1">Transportlar</td>
                     <td class="py-1">Boshlangan vaqt</td>
                     <td class="py-1">Tugagan vaqt</td>
                     <td class="py-1">Kutishga ketgan vaqti</td>
                  </tr>
                  <tr v-for="(car, index) in tableData" class="bg-zinc-800 border-y-4 border-zinc-900">
                     <td class="py-1 px-2">{{ index + 1 }}</td>
                     <td class="py-1">{{ car.name }}</td>
                     <td class="py-1">{{ moment(car.start).format('YYYY-MM-DD HH:mm:ss') }}</td>
                     <td class="py-1">{{ moment(car.end).format('YYYY-MM-DD HH:mm:ss') }}</td>
                     <td class="py-1">{{ secondsToFormatTime(car.difference) }}</td>
                  </tr>
               </table>
            </section>
            <section v-show="tab == 3" class="h-full !overflow-y-auto scroll indigo-scroll !overflow-hidden px-2">
               <table class="w-full">
                  <tr class="border-b-4 border-zinc-900 bg-stone-950">
                     <td class="py-1 px-2">№</td>
                     <td class="py-1">Transport nomi</td>
                     <td class="py-1">Kirgan vaqti</td>
                     <td class="py-1">Chiqqan vaqti</td>
                     <td class="py-1">Umumiy</td>
                     <td class="py-1 " v-if="props.color == 'indigo'">Sababi</td>

                  </tr>
                  <tr v-for="(transport, index) in selectedCars" class="bg-zinc-800 border-y-4 border-zinc-900">
                     <td class="py-1 px-2">{{ index + 1 }}</td>
                     <td class="py-1">{{ transport.name }}</td>
                     <td class="py-1">{{ moment(transport.geozone_in).format('YYYY-MM-DD HH:mm') }}</td>
                     <td class="py-1">{{ moment(transport.geozone_out).format('YYYY-MM-DD HH:mm') }}</td>
                     <td class="py-1">{{ getDifference(transport) }}</td>
                     <td class="py-1 w-52" v-if="props.color == 'indigo'">{{ transport.cause }}</td>
                  </tr>
               </table>
            </section>
         </div>
      </aside>
   </section>
</template>

<script setup lang="ts">
const props = defineProps(['group', 'color'])
import { ref, onMounted, reactive, computed } from 'vue'
import Highcharts from 'highcharts'
import moment from 'moment'
import { UTCTime, timeDiff, formatDate, getDateAndSmena, secondsToFormatTime, getDifference, inSmenaTime } from '@/helpers/timeFormat'
import { transportsTimeLine } from '@/conf/charts'

const tab = ref(3)

function setColor(boolean) {
   if (boolean) return `bg-${props.color}-600 text-white`
   else return 'bg-white text-gray-600'
}

const tableData = ref(null)


const fullWaitTime = computed(() => {
   return tableData.value?.reduce((summ, car) => summ + car.difference, 0)
})
const pickers = reactive(getDateAndSmena())

const handleDate = (modelData) => {
   pickers.date = modelData;
   getDiagramDate()
}

function changeSmena(smena) {
   pickers.smena = smena
   getDiagramDate()
}

const chartTimeLine = ref()
const selectedCars = ref()
function getDiagramDate() {
   axios.post('api/states/select_smena', { date: pickers.date, smena: pickers.smena })
      .then(({ data }) => {
         tableData.value = []
         selectedCars.value = data.states.filter((car) => car.geozone == props.group && timeDiff(car, 'seconds') > 59 && inSmenaTime(car) == false)

         selectedCars.value.forEach((selected, index, same) => {
            const startDate = moment(selected.geozone_in)
            const endDate = moment(selected.geozone_out)

            const conflicts = same.filter((car) => moment(car.geozone_in).isBetween(startDate, endDate))

            if (conflicts.length > 0) {
               conflicts.forEach((nagliCar) => {
                  const endAlso = moment(nagliCar.geozone_out).isBetween(startDate, endDate)

                  const difference = endAlso ? moment(nagliCar.geozone_out).diff(nagliCar.geozone_in, 'seconds') : endDate.diff(nagliCar.geozone_in, 'seconds')

                  tableData.value.push({
                     name: selected.name + ' va ' + nagliCar.name,
                     difference: difference,
                     start: nagliCar.geozone_in,
                     end: endAlso ? nagliCar.geozone_out : selected.geozone_out
                  })

               })
            }

         })

         const sett = new Set(selectedCars.value.map((car) => car.name))
         const carNames = [...sett]


         const chartData = selectedCars.value.map((car) => {
            return {
               start: UTCTime(car.geozone_in),
               end: UTCTime(car.geozone_out),
               y: carNames.findIndex((search) => search == car.name),
               carName: car.name,
            }
         })


         const option: any = transportsTimeLine(chartData, carNames, data.smena)
         Highcharts.ganttChart(chartTimeLine.value, option)
      })

}

onMounted(() => getDiagramDate())
</script>