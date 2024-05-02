<template>
   <section @mousedown="$emit('close')" class="fixed inset-0 bg-zinc-950/70 flex justify-center items-center z-50">
      <aside @mousedown.stop class="w-[1100px] h-[540px]">
         <div class="py-2 slider-item">
            <div>
               <h3 class="flex gap-1 px-2 pt-1 rounded font-semibold h-10">
                  <div class="w-40">
                     <VueDatePicker
                        @update:model-value="handleDate"
                        v-model="pickers.date"
                        :format="formatDate"
                        auto-apply
                        placeholder="Kunni tanlang"
                     />
                  </div>
                  <div class="flex gap-1 mx-1">
                     <button @click="changeSmena(1)" :class="Color(1)" class="w-10 text-black rounded shadow">
                        1
                     </button>
                     <button @click="changeSmena(2)" :class="Color(2)" class="w-10 text-black rounded shadow">
                        2
                     </button>
                  </div>

                  <div class="flex gap-1 mx-1">
                     <button @click="tab = 1" :class="tabColor(1)" class="w-20 h-full font-semibold rounded shadow">
                     <i class="fa-solid fa-chart-gantt"></i>
                  </button>
                  <button @click="tab = 2" :class="tabColor(2)" class="w-20 h-full font-semibold rounded shadow">
                     <i class="fa-solid fa-table-list"></i>
                  </button>
                  </div>
                  <div :class="`bg-${props.color}-600`" class="flex-grow text-center h-full flex items-center justify-center  rounded shadow">
                     {{ props.group }}
                  </div>

               </h3>
            </div>
            <section v-show="tab == 1" ref="chartTimeLine" class="h-full !overflow-y-auto scroll indigo-scroll !overflow-hidden"></section>
            <section v-show="tab == 2" class="h-full !overflow-y-auto scroll indigo-scroll !overflow-hidden"></section>
         </div>
      </aside>
   </section>
</template>

<script setup lang="ts">
const props = defineProps(['group', 'color'])
import { ref, onMounted, reactive } from 'vue'
import Highcharts from 'highcharts'
import { UTCTime, timeDiff, formatDate, getDateAndSmena } from '@/helpers/timeFormat'
import { transportsTimeLine } from '@/conf/charts'


const tab = ref(1)


function Color(smena) {
   if (pickers.smena == smena) return `bg-${props.color}-600 text-white`
   else return 'bg-white'
}

function tabColor(smena) {
   if (tab.value == smena) return `bg-${props.color}-600 text-white`
   else return 'bg-white text-gray-600'
}


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

function getDiagramDate() {
   axios.post('api/states/select_smena', { date: pickers.date, smena: pickers.smena })
      .then(({ data }) => {
         const group = data.states.filter((state) => state.geozone == props.group && timeDiff(state, 'seconds') > 10)

         const sett = new Set(group.map((car) => car.name))
         const carNames = [...sett]


         const chartData = group.map((car) => {
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

