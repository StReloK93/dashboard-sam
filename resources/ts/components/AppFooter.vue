<template>
   <footer class="bg-zinc-900 xl:px-5 px-2 shadow flex flex-wrap content-center xl:gap-1 gap-0.5 justify-between neomorph relative">
      <div v-for="car in store.ExcavatorList"
         :class="[car.status_of == 'Ishda' ? 'border-teal-400 neomorph bg-zinc-800' : '!border-transparent inner-shadow-second']"
         class="text-xs 2xl:w-16 xl:w-14 2xl:h-16 xl:h-14 lg:w-10 lg:h-10 w-8 h-8 rounded-full text-center inline-flex items-center justify-center flex-col border-2">
         <main class="text-white 2xl:text-xl xl:text-base leading-none font-semibold">
            {{ car.number }}
         </main>
         <span class="text-gray-500 2xl:text-xs xl:text-[10px] xl:inline-block hidden leading-none">
            {{ car.name }}
         </span>
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