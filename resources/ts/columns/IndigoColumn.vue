<template>
   <main class="border-r border-zinc-800 flex flex-col">
      <div class="xl:h-24 h-[72px] flex items-center justify-around">
         <ColumnTopSlider :slides="indigoSlides" />
      </div>
      <aside class="indigo-scroll overflow-y-auto flex-grow scroll overflow-x-hidden relative">
         <TransportProcess counter="reys" :data="[]" grid-cols="grid-cols-3" color="yellow" class="opacity-0" />
         <main class="absolute inset-0">
            <TransportProcessGroup grid-cols="grid-cols-3" @openModal="(transport) => store.openModal(3, transport)"
               :title="$t('inchange')" color="indigo" scroll-color="indigo-scroll" :data="transportStore.inSMENA" />
         </main>
      </aside>
   </main>
</template>

<script setup lang="ts">
import { Transports, TransportModal } from '@/entities/transports'
import ColumnTopSlider from '@/components/ColumnTopSlider.vue'
import TransportProcess from '@/components/TransportProcess.vue'
import { reactive, computed } from 'vue'
import TransportProcessGroup from '@/components/TransportProcessGroup.vue'
const store = TransportModal()
const transportStore = Transports()


const indigoSlides = reactive([
   {
      bgColor: 'stroke-indigo-500',
      textColor: 'text-indigo-400',
      timer: 30,
      value: computed(() => {
         var summa = 0
         for (const key in transportStore.inSMENA) {
            summa += transportStore.inSMENA[key].cars.length
         }
         return summa
      }),
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