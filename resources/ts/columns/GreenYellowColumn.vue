<template>
   <main class="border-r border-zinc-800 flex flex-col xl:flex-grow-0 flex-grow">
      <div class="xl:h-28 h-24 px-2 flex items-center justify-around">
         <ColumnTopSlider :slides="greenSlides" />
         <ColumnTopSlider :slides="yellowSlides" />
      </div>
      <aside class="green-scroll overflow-y-auto flex-grow scroll overflow-x-hidden relative grid-col">
         <TransportProcess counter="reys" :data="[]" :grid-cols="`xl:grid-cols-5 grid-cols-3`" color="yellow" class="opacity-0" />
         <main class="absolute inset-0">
            <TransportProcess counter="reys" :data="transportStore.inProcess" :grid-cols="`xl:grid-cols-5 grid-cols-3`"
               @openModal="(transport) => store.openModal(0, transport)" :title="$t('inprocess')" color="green" />
            <TransportProcess counter="timer" :data="transportStore.inExcavator" :grid-cols="`xl:grid-cols-5 grid-cols-3`"
               @openModal="(transport) => store.openModal(1, transport)" :title="$t('inexcavator')" color="yellow" />
         </main>
      </aside>
   </main>
</template>

<script setup lang="ts">
import ColumnTopSlider from '@/components/ColumnTopSlider.vue'
import TransportProcess from '@/components/TransportProcess.vue'
import { Transports, TransportModal } from '@/entities/transports'
import { Excavators } from '@/entities/transports/Excavators'
import { reactive, computed } from 'vue'
import { useTruckState } from '@/entities/transports/truckstate/TruckStateStore'

const truckStateStore = useTruckState()
const store = TransportModal()
const transportStore = Transports()
const excavatorStore = Excavators()
const pageSettings = settings
const greenSlides = reactive([
   {
      onStart: () => {
         if(truckStateStore.isFirstLoading == false) truckStateStore.fetchData()
         if (pageSettings.excavators) excavatorStore.getExcavatorStates()
      },
      bgColor: 'stroke-green-600',
      textColor: 'text-green-400',
      timer: 30,
      value: computed(() => transportStore.inProcess?.length),
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