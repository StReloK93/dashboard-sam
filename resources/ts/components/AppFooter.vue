<template>
   <footer class="bg-zinc-900 px-5 shadow flex flex-wrap content-center gap-1 justify-between neomorph relative">
      <div v-for="car in excavators" :class="[
         +car.status == 1 && (moment().diff(car.msg_dt, 'minutes') < 15) ? 'border-teal-400 neomorph bg-zinc-800' : '',
         car.status == 0 || moment().diff(car.msg_dt, 'minutes') >= 15 ? '!border-transparent inner-shadow-second' : ''
      ]"
      class="text-xs w-16 h-16 rounded-full text-center inline-flex items-center justify-center flex-col border-2"
      >
         <main class="text-white text-xl leading-none font-semibold">
            {{ car.garage }}
         </main>
         <span class="text-gray-500 text-xs">
            {{ car.tech_name }}
         </span>
      </div>
   </footer>
</template>

<script setup lang="ts">
import { Transports } from '@/entities/transports'
import moment from 'moment'
import { computed } from 'vue'
const carState = Transports()


const excavators = computed(() => {
   return carState.excavatorStates?.map(car => {
      car.tech_name = car.tech_name.replace('HITACHI ', '').replace('(обратная лопата)', '')
      return car
   })
})
</script>