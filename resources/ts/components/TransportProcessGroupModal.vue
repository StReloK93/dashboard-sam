<template>
   <section @mousedown="$emit('close')"
      class="fixed inset-0 bg-zinc-950/70 backdrop-blur-[2px] flex justify-center items-center z-50">
      <aside @mousedown.stop class="w-[1100px] h-[540px] relative">
         <PreLoader v-if="loader" />
         <div class="slider-item">
            <div class="mb-1.5">
               <div :class="`bg-${props.color}-600`"
                  class="text-center mx-2 xl:h-9 h-7 flex items-center justify-center rounded shadow font-semibold text-xl">
                  {{ props.group }}
               </div>
               <h3 class="flex gap-1 px-2 mt-1.5 rounded font-semibold xl:h-9 h-7">
                  <div class="w-40 xl:h-9 h-7">
                     <VueDatePicker @update:model-value="handleDate" v-model="pickers.date" :format="formatDate"
                        auto-apply placeholder="Kunni tanlang" />
                  </div>
                  <div class="flex gap-1 ml-1">
                     <button @click="changeSmena(1)" :class="setColor(pickers.smena == 1)"
                        class="xl:w-24 w-20 rounded shadow  xl:text-base text-xs">
                        1 - {{ $t('change') }}
                     </button>
                     <button @click="changeSmena(2)" :class="setColor(pickers.smena == 2)"
                        class="xl:w-24 w-20 rounded shadow  xl:text-base text-xs">
                        2 - {{ $t('change') }}
                     </button>
                  </div>
                  <div class="flex gap-1 ml-1 flex-grow">
                     <button @click="tab = 1" :class="setColor(tab == 1)"
                        class="xl:w-24 w-20 h-full font-semibold rounded shadow xl:text-base text-xs">
                        <i class="fa-solid fa-chart-gantt"></i> {{ $t('grafic') }}
                     </button>
                     <button @click="tab = 2" :class="setColor(tab == 2)"
                        class="xl:w-24 w-20 h-full font-semibold rounded shadow xl:text-base text-xs">
                        <i class="fa-solid fa-table-list"></i> {{ $t('table') }}
                     </button>
                     <button v-if="props.color == 'orange'" @click="tab = 3" :class="setColor(tab == 3)"
                        class="px-2 h-full font-semibold rounded shadow ml-1 flex-grow  xl:text-base text-xs">
                        <i class="fa-solid fa-table-list"></i> {{ $t('waitinginoil') }}
                        <span v-if="fullWaitTime">
                           {{ secondsToFormatTime(fullWaitTime) }}
                        </span>
                        <span v-else>
                           00:00:00
                        </span>
                     </button>
                     <button v-if="props.color == 'indigo'" @click="tab = 4" :class="setColor(tab == 4)"
                        class="px-2 h-full font-semibold rounded shadow ml-1 flex-grow  xl:text-base text-xs">
                        <i class="fa-solid fa-table-list"></i> {{ $t('inremontcause') }}
                     </button>
                  </div>
               </h3>
            </div>
            <TransportProcessGroupChart v-if="selectedCars.length && dataSmena" v-show="tab == 1"
               :selectedCars="selectedCars" :dataSmena="dataSmena" />
            <TransportProcessGroupTable v-if="selectedCars.length" v-show="tab == 2" :selectedCars="selectedCars"
               :color="props.color" />
            <TransportProcessGroupOilTable v-if="selectedCars.length && props.color == 'orange'"
               @table-update="(data) => tableData = data" v-show="tab == 3" :selectedCars="selectedCars" />
            <TransportProcessGroupCauses v-if="selectedCars.length && props.color == 'indigo'"
               @table-update="(data) => tableData = data" v-show="tab == 4" :selectedCars="selectedCars"
               :color="props.color" />
         </div>
      </aside>
   </section>
</template>

<script setup lang="ts">
import TransportProcessGroupChart from './TransportProcessGroupChart.vue'
import TransportProcessGroupTable from './TransportProcessGroupTable.vue'
import TransportProcessGroupOilTable from './TransportProcessGroupOilTable.vue'
import TransportProcessGroupCauses from './TransportProcessGroupCauses.vue'
import { ref, onMounted, reactive, computed } from 'vue'
import TruckStateRepository from '@/entities/transports/truckstate/TruckStateRepository'
import { timeDiff, formatDate, getDateAndSmena, secondsToFormatTime, inSmenaTime } from '@/helpers/timeFormat'
const props = defineProps(['group', 'color'])

const tab = ref(2)




function setColor(boolean) {
   if (boolean) return `bg-${props.color}-600 text-white`
   else return 'bg-white text-gray-600'
}

const tableData = ref(null)
const loader = ref(false)
const dataSmena = ref(null)
const fullWaitTime = computed(() => {
   return tableData.value?.reduce((summ, car) => summ + car.difference, 0)
})
const pickers = reactive(getDateAndSmena())
console.log(pickers);

const handleDate = (modelData) => {
   pickers.date = modelData;
   getDiagramDate()
}

function changeSmena(smena) {
   pickers.smena = smena
   getDiagramDate()
}


const group_id = props.color  == 'sky' ?  settings.WATERTRUCKS : settings.DUMPTRUCKS

const selectedCars = ref([])
async function getDiagramDate() {
   loader.value = true
   TruckStateRepository.selectSmena({ date: pickers.date, smena: pickers.smena, group_ids: [group_id]  }, ({ data }) => {
      dataSmena.value = data.smena
      selectedCars.value = data.states.filter((car) => (car.geozone == props.group) && (timeDiff(car, 'seconds') > 59) && (inSmenaTime(car) == false))
      .sort((a, b) => a.id - b.id)
      
      loader.value = false
   })


}

onMounted(() => getDiagramDate())
</script>