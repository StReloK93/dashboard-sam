<template>
   <section class="h-full !overflow-y-auto scroll indigo-scroll !overflow-hidden px-2">
      <table class="w-full">
         <tr class="border-b-4 border-zinc-900 bg-stone-950">
            <td class="py-1 px-2 w-8">№</td>
            <td class="py-1 w-40">Transport nomi</td>
            <td class="py-1 w-40">Kirgan vaqti</td>
            <td class="py-1 w-40">Chiqqan vaqti</td>
            <td class="py-1 w-24">Umumiy</td>
            <td class="py-1 px-2 max-w-80" v-if="props.color == 'indigo'">Sababi</td>

         </tr>
         <tr v-for="(transport, index) in selectedCars" class="bg-zinc-800 border-y-4 border-zinc-900">
            <td class="py-1 px-2 w-8">{{ index + 1 }}</td>
            <td class="py-1 w-40">{{ transport.name }}</td>
            <td class="py-1 w-40">{{ moment(transport.geozone_in).format('YYYY-MM-DD HH:mm') }}</td>
            <td class="py-1 w-40">{{ moment(transport.geozone_out).format('YYYY-MM-DD HH:mm') }}</td>
            <td class="py-1 w-24">{{ getDifference(transport) }}</td>
            <td class="py-1 pl-2  max-w-80" v-if="props.color == 'indigo'">
               <EditTransportCause :type="props.color" :transport="transport" :causes="causes" />
            </td>
         </tr>
      </table>
   </section>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import EditTransportCause from './EditTransportCause.vue';
import moment from 'moment'
import { getDifference } from '@/helpers/timeFormat'
const props = defineProps(['selectedCars', 'color'])

const causes = ref([])

onMounted(() => {
   axios.get(`api/get-cause-list`).then(({ data }) => causes.value = data)
})
</script>

<!-- select * from wialon.dbo.ex_status('2024-07-08', 1) -->