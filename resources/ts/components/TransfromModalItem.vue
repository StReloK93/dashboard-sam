<template>
   <section class="h-full w-full overflow-y-auto px-1">
      <table class="w-full">
         <tr class="border-b-4 border-zinc-900">
            <td :class="props.headerColor" class="py-1 rounded-tl px-2 w-10">№</td>
            <td :class="props.headerColor" class="py-1 w-40">Geozona</td>
            <td :class="props.headerColor" class="py-1 w-36">Kirgan vaqti</td>
            <td :class="props.headerColor" class="py-1 w-36">Chiqqan vaqti</td>
            <td :class="props.headerColor" class="py-1 w-20">Umumiy</td>

            <td :class="props.headerColor" class="py-1 rounded-tr max-w-52" v-if="['inATB', 'inSmenaAll'].includes(props.type)">
               Sababi
            </td>

         </tr>
         <tr v-for="(transport, index) in states[props.type]" class="bg-zinc-800 border-y-4 border-zinc-900">
            <td class="py-1 px-2 w-10">{{ index + 1 }}</td>
            <td class="py-1 w-40">{{ transport.geozone ? transport.geozone : "---" }}</td>
            <td class="py-1 w-36">{{ moment(transport.geozone_in).format('YYYY-MM-DD HH:mm') }}</td>
            <td class="py-1 w-36">{{ moment(transport.geozone_out).format('YYYY-MM-DD HH:mm') }}</td>
            <td class="py-1 w-20">{{ getDifference(transport) }}</td>

            <td class="py-1 max-w-52" v-if="['inATB', 'inSmenaAll'].includes(props.type)">
               <EditTransportCause :transport="transport" :causes="causes" :type="props.type" />
            </td>

         </tr>
      </table>
   </section>
</template>

<script setup lang="ts">
import EditTransportCause from './EditTransportCause.vue';
import moment from 'moment'
import { getDifference } from '@/helpers/timeFormat'
import { TransportStates } from '@/entities/transports'
import { inject, ref } from 'vue'
const props = defineProps(['type', 'headerColor'])
const causes = inject('causes')
const states = TransportStates()
</script>