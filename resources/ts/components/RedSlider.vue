<template>
   <section class="h-full w-full overflow-y-auto px-1">
      <table class="w-full">
         <tr class="border-b-4 border-zinc-900">
            <td :class="props.headerColor" class="py-1 rounded-tl px-2">№</td>
            <td :class="props.headerColor" class="py-1">{{$t('geozone')}}</td>
            <td :class="props.headerColor" class="py-1">{{$t('timein')}}</td>
            <td :class="props.headerColor" class="py-1">{{$t('timeout')}}</td>
            <td :class="props.headerColor" class="py-1">{{$t('timeall')}}</td>

         </tr>
         <tr v-for="(transport, index) in states[props.type]" class="bg-zinc-800 border-y-4 border-zinc-900">
            <td class="py-1 px-2">{{index + 1}}</td>
            <td class="py-1">{{ transport.geozone ? transport.geozone : "---"  }}</td>
            <td class="py-1">{{ moment(transport.geozone_in).format('YYYY-MM-DD HH:mm') }}</td>
            <td class="py-1">{{ moment(transport.geozone_out).format('YYYY-MM-DD HH:mm') }}</td>
            <td class="py-1">{{ getDifference(transport) }}</td>
         </tr>
      </table>
   </section>
</template>

<script setup lang="ts">
import moment from 'moment'
import { getDifference } from '@/helpers/timeFormat'
import { TransportStates } from '@/entities/transports'
const props = defineProps(['type', 'headerColor', 'transport_id'])
const states = TransportStates()
</script>