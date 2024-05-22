<template>
   <main class="border-r border-zinc-800 flex flex-col">
      <div class="h-28 px-2 flex items-center justify-around">
         <ColumnTopSlider :slides="indigoSlides" />
      </div>
      <aside class="green-scroll overflow-y-auto flex-grow scroll overflow-x-hidden">
         <TransportProcessGroup grid-cols="grid-cols-3" @openModal="(transport) => store.openModal(3, transport)"
            title="Smena almashish maydonida" color="indigo" scroll-color="indigo-scroll" :data="transportStore.inSMENA" />
      </aside>
   </main>
</template>

<script setup lang="ts">
import { Transports, TransportModal } from '@/entities/transports'
import ColumnTopSlider from '@/components/ColumnTopSlider.vue'
import { reactive, computed } from 'vue'
import TransportProcessGroup from '@/components/TransportProcessGroup.vue'
const store = TransportModal()
const transportStore = Transports()


const indigoSlides = reactive([
   {
      bgColor: 'stroke-indigo-500',
      textColor: 'text-indigo-400',
      timer: 30,
      value: computed(() => transportStore.inSmenaAll?.length),
      component: 'TruckIcon',
      componentParams: { width: 22, color: "fill-indigo-400", colorSecond: "fill-indigo-900" },
   },
   {
      bgColor: 'stroke-indigo-400',
      textColor: 'text-indigo-400',
      timer: 30,
      type: 'time',
      value: computed(() => transportStore.statesSumm.summSmenaTime),
      icon: "fa-duotone fa-hourglass-clock"
   },
   {
      bgColor: 'stroke-indigo-400',
      textColor: 'text-indigo-400',
      timer: 30,
      value: computed(() => transportStore.summaSmenaCars),
      icon: "fa-solid fa-layer-group"
   },
])
</script>