<template>
   <footer class="bg-zinc-900 px-5 shadow flex flex-wrap content-center gap-1 justify-between neomorph relative">
      <div v-for="car in excavators" :class="[+car.status == 1 ? 'neomorph bg-zinc-800  border-2 border-teal-400': 'inner-shadow-second']"
         class="text-xs w-16 h-16 rounded-full  text-center inline-flex items-center justify-center flex-col">
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
import { computed } from 'vue';
const carState = Transports()


const excavators = computed(() => {
   return carState.excavatorStates?.map(car => {
      car.tech_name = car.tech_name.replace('HITACHI ', '').replace('(обратная лопата)', '')
      return car
   })
})
</script>