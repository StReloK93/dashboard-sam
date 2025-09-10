<template>
   <footer class="bg-zinc-900 xl:px-3 px-1.5 shadow flex flex-wrap content-center xl:gap-1 gap-0.5 justify-between neomorph relative">
      <div v-for="car in store.ExcavatorList"
         :class="[car.status_of == 'Ishda' ? '!border-teal-400 neomorph bg-zinc-800' : '!border-zinc-950 inner-shadow-second']"
         class="text-xs xl:w-10 xl:h-10 w-7 h-7 rounded-full text-center inline-flex items-center justify-center flex-col border-2 ">
         <main class="text-teal-400 xl:text-base font-semibold">
            {{ car.number }}
         </main>
         <tippy v-if="car.cause_old && car.status_of != 'Ishda'" target="_parent">
            <div class="font-semibold">{{car.cause_old}}</div>
         </tippy>
      </div>
   </footer>
</template>

<script setup lang="ts">
import { Excavators } from '@/entities/transports/Excavators'
const store = Excavators()
</script>