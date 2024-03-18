<template>
   <section @mouseup="$emit('close')" class="fixed inset-0 bg-zinc-950/70 flex justify-center items-center z-50">
      <aside @mouseup.stop class="w-[850px] h-[540px] relative">
         <main :class="`border-t-${props.color}-600`" class="slider-item border-t-2  py-1">
            <section :class="`${props.color}-scroll`" class="h-full w-full overflow-y-auto px-1 scroll ">
               <table class="w-full">
                  <tr>
                     <td colspan="5" :class="`bg-${props.color}-600`" class="py-1 text-center">{{ props.filter }}</td>
                  </tr>
                  <tr class="border-b-4 border-zinc-900">
                     <td class="py-1 rounded-tl px-2">№</td>
                     <td class="py-1">Transport nomi</td>
                     <td class="py-1">Kirgan vaqti</td>
                     <td class="py-1">Chiqqan vaqti</td>
                     <td class="py-1 rounded-tr">Umumiy</td>
                  </tr>
                  <tr v-for="(transport, index) in cars" class="bg-zinc-800 border-y-4 border-zinc-900">
                     <td class="py-1 px-2">{{ index + 1 }}</td>
                     <td class="py-1">{{ transport.name }}</td>
                     <td class="py-1">{{ moment(transport.geozone_in).format('YYYY-MM-DD HH:mm') }}</td>
                     <td class="py-1">{{ moment(transport.geozone_out).format('YYYY-MM-DD HH:mm') }}</td>
                     <td class="py-1">{{ getDifference(transport) }}</td>
                  </tr>
               </table>
            </section>
         </main>
      </aside>
   </section>
</template>

<script setup lang="ts">
import moment from 'moment'
import { getDifference } from '@/helpers/timeFormat'
import { Transports } from '@/entities/transports/Transports'
const props = defineProps(['filter', 'color'])
const store = Transports()
const cars = store.getFilterGroup(props.filter, props.color)
</script>