<template>
   <main class="border-r border-zinc-800 flex flex-col">
      <div class="h-28 px-2 flex items-center justify-around">
         <ColumnTopSlider :slides="greenSlides" />
         <ColumnTopSlider :slides="yellowSlides" />
      </div>
      <aside class="green-scroll overflow-y-auto flex-grow scroll overflow-x-hidden relative">
         <TransportProcess counter="reys" :data="[]" grid-cols="grid-cols-4" color="yellow" class="opacity-0"/>
         <main class="absolute inset-0">
            <TransportProcess counter="reys" :data="transportStore.inProcess" grid-cols="grid-cols-4"
               @openModal="(transport) => store.openModal(0, transport)" title="Ish jarayonida" color="green" />
            <TransportProcess counter="timer" :data="transportStore.inExcavator" grid-cols="grid-cols-4"
               @openModal="(transport) => store.openModal(1, transport)" title="Kutish jarayonida" color="yellow" />
         </main>
      </aside>
   </main>
</template>

<script setup lang="ts">
import ColumnTopSlider from '@/components/ColumnTopSlider.vue'
import TransportProcess from '@/components/TransportProcess.vue'
import { Transports, TransportModal } from '@/entities/transports'
import { reactive, computed } from 'vue'
const store = TransportModal()
const transportStore = Transports()

const greenSlides = reactive([
   {
      onStart: transportStore.getTransports,
      bgColor: 'stroke-green-600',
      textColor: 'text-green-400',
      timer: 30,
      value: computed(() => transportStore.inProcess.length),
      component: 'TruckIcon',
      componentParams: { width: 22, color: "fill-green-500", colorSecond: "fill-green-900" },
   },
   {
      bgColor: 'stroke-green-400',
      textColor: 'text-green-400',
      timer: 30,
      icon: "fa-regular fa-sigma",
      value: computed(() => transportStore.statesSumm.reysCount),
   },
])


const yellowSlides = reactive([
   {
      bgColor: 'stroke-yellow-600',
      textColor: 'text-yellow-400',
      timer: 30,
      value: computed(() => transportStore.inExcavator?.length),
      component: 'TruckIcon',
      componentParams: { width: 22, color: "fill-yellow-500", colorSecond: "fill-yellow-900" },
   },
   {
      bgColor: 'stroke-yellow-400',
      textColor: 'text-yellow-400',
      timer: 30,
      type: 'time',
      icon: "fa-duotone fa-hourglass-clock",
      value: computed(() => transportStore.statesSumm.summExcavatorTime),
   },
])
</script>