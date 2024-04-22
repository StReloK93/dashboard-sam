<template>
   <section class="h-full w-full overflow-y-auto px-1">
      <table class="w-full">
         <tr class="border-b-4 border-zinc-900">
            <td :class="props.headerColor" class="py-1 rounded-tl px-2">№</td>
            <td :class="props.headerColor" class="py-1">Geozona</td>
            <td :class="props.headerColor" class="py-1">Kirgan vaqti</td>
            <td :class="props.headerColor" class="py-1">Chiqqan vaqti</td>
            <td :class="props.headerColor" class="py-1">Umumiy</td>
            <td :class="props.headerColor" class="py-1  rounded-tr" v-if="props.type == 'inATB' || props.type == 'inSmenaAll'">Sababi</td>
         </tr>
         <tr v-for="(transport, index) in states[props.type]" class="bg-zinc-800 border-y-4 border-zinc-900">
            <td class="py-1 px-2">{{index + 1}}</td>
            <td class="py-1">{{ transport.geozone ? transport.geozone : "---"  }}</td>
            <td class="py-1">{{ moment(transport.geozone_in).format('YYYY-MM-DD HH:mm') }}</td>
            <td class="py-1">{{ moment(transport.geozone_out).format('YYYY-MM-DD HH:mm') }}</td>
            <td class="py-1">{{ getDifference(transport) }}</td>
            <td class="py-1 w-52" v-if="props.type == 'inATB' || props.type == 'inSmenaAll'">
               <form @submit.prevent="saveCause(transport)" class="w-full flex items-center pr-2">
                  <input :disabled="transport.bool" type="text" v-model="transport.cause" class="outline-none relative bg-gray-700 shadow-inner px-1.5 py-0.5 disabled:bg-transparent rounded-l-sm">
                  <template v-if="(auth.user?.level == 0 && props.type == 'inATB') || (auth.user?.level == 1 && props.type == 'inSmenaAll')">
                     <button v-if="transport.bool" @click="transport.bool = false" type="button" class="w-10 py-0.5 bg-gray-300 rounded-r-sm shadow active:bg-gray-500 hover:bg-gray-400">
                        <i class="fa-duotone fa-pen-nib text-gray-900"></i>
                     </button>
                     <button v-else type="submit" class="w-10 py-0.5 bg-gray-300 rounded-sm shadow active:bg-gray-500 hover:bg-gray-400">
                        <i class="fa-duotone fa-floppy-disk text-gray-900"></i>
                     </button>
                  </template>
               </form>
            </td>
         </tr>
      </table>
   </section>
</template>

<script setup lang="ts">
import moment from 'moment'
import { getDifference } from '@/helpers/timeFormat'
import { TransportStates } from '@/entities/transports'
import { useAuthStore } from '@/app/auth'
import axios from 'axios'
const props = defineProps(['type', 'headerColor'])
const auth = useAuthStore()

const states = TransportStates()


function saveCause(transport) {
   axios.patch(`api/transportstates/${transport.id}`, transport).then(({data}) => {
      console.log(data)
   })
   transport.bool = true
}
</script>