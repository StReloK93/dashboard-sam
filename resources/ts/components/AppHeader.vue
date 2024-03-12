<template>
   <main class="bg-zinc-800 py-1 px-5 shadow text-white flex items-center justify-between">
      <span>
         {{ store.timer }}
      </span>
      <div class="flex items-center">
         <section class="flex items-center mx-3">
            <span class="mx-4">
               Minut
            </span>
            <aside class="w-2 h-9 relative flex justify-center items-center text-xs">
               <span
                  class="w-6 h-6 bg-gray-400 rounded-full flex-shrink-0 text-gray-900 flex justify-center items-center font-bold">
                  1
               </span>
            </aside>
         </section>
         <section class="flex items-center mx-3">
            <span class="mx-4">
               Soat
            </span>
            <aside class="w-2 h-9 bg-yellow-400 relative flex justify-center items-center text-xs">
               <span
                  class="w-6 h-6 bg-gray-400 rounded-full flex-shrink-0 text-gray-900 flex justify-center items-center font-bold">
                  1
               </span>
            </aside>
         </section>
         <section class="flex items-center mx-3">
            <span class="mx-4">
               Kun
            </span>
            <aside class="w-2 h-9 bg-gray-300 relative flex justify-center items-center text-xs">
               <span
                  class="w-6 h-6 bg-gray-400 rounded-full flex-shrink-0 text-gray-900 flex justify-center items-center font-bold">
                  1
               </span>
            </aside>
         </section>
      </div>
   </main>
</template>

<script setup lang="ts">
import { Transports } from '@/entities/transports'
const store = Transports()
</script>