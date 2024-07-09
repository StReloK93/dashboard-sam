<template>
   <footer class="bg-zinc-900 px-5 shadow flex flex-wrap content-center gap-1 justify-between neomorph relative">
      <div v-for="car in store.ExcavatorList"
         :class="[car.status_of == 'Ishda' ? 'border-teal-400 neomorph bg-zinc-800' : '!border-transparent inner-shadow-second']"
         class="text-xs w-16 h-16 rounded-full text-center inline-flex items-center justify-center flex-col border-2">
         <main class="text-white text-xl leading-none font-semibold">
            {{ car.number }}
         </main>
         <span class="text-gray-500 text-xs">
            {{ car.name }}
         </span>
      </div>
   </footer>
</template>

<script setup lang="ts">
import { Excavators } from '@/entities/transports/Excavators'
const store = Excavators()
</script>