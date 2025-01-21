<template>
   <section class="h-full !overflow-y-auto scroll indigo-scroll !overflow-hidden px-2">
      <table class="w-full xl:text-base text-xs">
         <tr class="border-b-4 border-zinc-900 bg-stone-950">
            <td class="py-1 rounded-tl px-2">№</td>
            <td class="py-1">Transportlar</td>
            <td class="py-1">Boshlangan vaqt</td>
            <td class="py-1">Tugagan vaqt</td>
            <td class="py-1">Kutishga ketgan vaqti</td>
            <td class="py-1">Sababi</td>
         </tr>
         <tr v-for="(car, index) in tableData" class="bg-zinc-800 border-y-4 border-zinc-900">
            <td class="py-1 px-2">{{ index + 1 }}</td>
            <td class="py-1">{{ car.name }}</td>
            <td class="py-1">{{ moment(car.start).format('YYYY-MM-DD HH:mm:ss') }}</td>
            <td class="py-1">{{ moment(car.end).format('YYYY-MM-DD HH:mm:ss') }}</td>
            <td class="py-1">{{ secondsToFormatTime(car.difference) }}</td>
            <td class="py-1 max-w-80">
               <EditTransportCause :type="props.color" :transport="car" :causes="causes" />
            </td>
         </tr>
      </table>
   </section>
</template>

<script setup lang="ts">
import EditTransportCause from './EditTransportCause.vue';
import moment from 'moment'
import { secondsToFormatTime } from '@/helpers/timeFormat'
import { watch, ref, onMounted } from 'vue'
const props = defineProps(['selectedCars', 'color'])
const emit = defineEmits(['table-update'])


const causes = ref([])



const tableData = ref([])
watch(() => props.selectedCars, () => updateChart())

function updateChart() {
   tableData.value = []
   const issetCaueseCars = props.selectedCars.filter((car) => car.cause != null && car.cause != "113")
   
   issetCaueseCars.forEach((selected, index, same) => {
      selected.bool = true

      const startDate = moment(selected.geozone_in)
      const endDate = moment(selected.geozone_out)

      const conflicts = same.filter((car) => moment(car.geozone_in).isBetween(startDate, endDate) && car.cause == selected.cause)

      if (conflicts.length > 0) {
         conflicts.forEach((nagliCar) => {
            const endAlso = moment(nagliCar.geozone_out).isBetween(startDate, endDate)

            const difference = endAlso ? moment(nagliCar.geozone_out).diff(nagliCar.geozone_in, 'seconds') : endDate.diff(nagliCar.geozone_in, 'seconds')

            tableData.value.push({
               name: selected.name + ' va ' + nagliCar.name,
               difference: difference,
               start: nagliCar.geozone_in,
               end: endAlso ? nagliCar.geozone_out : selected.geozone_out,
               cause: selected.cause
            })

         })
      }
   })

   emit('table-update', tableData.value)
}




onMounted(() => {
   updateChart()
   axios.get(`api/get-cause-list`).then(({ data }) => causes.value = data)
})
</script>

