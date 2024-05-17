<template>
   <section @mouseup="$emit('close')" class="fixed inset-0 bg-zinc-950/70 flex justify-center items-center z-50">
      <aside @mouseup.stop class="w-[992px] h-[540px] relative">
         <main class="slider-item py-1">
            <div class="px-1">
               <div class="text-center mb-1.5 flex font-semibold gap-1.5">
                  <button @click="tab = 1" :class="{'!bg-indigo-600': tab == 1}" class="content-center px-4 bg-zinc-700 h-9 rounded shadow">
                     Haftalik
                  </button>
                  <button @click="tab = 2" :class="{'!bg-indigo-600': tab == 2}" class="content-center px-4 bg-zinc-700 h-9 rounded shadow">
                     Chorak
                  </button>
                  <span class="content-center flex-grow bg-zinc-700 h-9 rounded shadow  text-xl">
                     Avtoag'dargichlarni ishga tayyorligi bo'yicha hisobot
                  </span>
               </div>
            </div>
            <TransitionGroup>
               <ReportModalWeekly v-if="tab == 1" />
               <ReportModalQuarter v-if="tab == 2"  />
            </TransitionGroup>
         </main>
      </aside>
   </section>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import ReportModalWeekly from '@/components/ReportModalWeekly.vue'
import ReportModalQuarter from '@/components/ReportModalQuarter.vue'

const tab = ref(1)
</script>

