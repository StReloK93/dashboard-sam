<template>
   <main class="border-r border-zinc-800 flex flex-col">
      <div class="xl:h-28 h-24 px-2 flex items-center justify-around">
         <ColumnTopSlider :slides="redSlides" />
      </div>
      <aside class="red-scroll overflow-y-auto flex-grow scroll overflow-x-hidden relative">
         <TransportProcess counter="reys" :data="[]" grid-cols="grid-cols-2" color="yellow" class="opacity-0"/>
         <main class="absolute inset-0">
            <TransportProcess counter="timer" grid-cols="grid-cols-2"
               @openModal="(transport) => store.openModal(4, transport)" :title="$t('inred')" color="red"
               :data="transportStore.isUnknown" />
            <hr class="my-4 border-zinc-800">
            <TransportProcessGroup v-if="envSettings.gusaks" grid-cols="grid-cols-2" :title="$t('ingusak')" color="sky" scroll-color="sky-scroll" :data="waterTrucks.inGUSAK" />
         </main>
      </aside>
   </main>
</template>

<script setup lang="ts">
import { reactive, computed } from 'vue'
import ColumnTopSlider from '@/components/ColumnTopSlider.vue'
import { Transports, TransportModal, WaterTrucks } from '@/entities/transports'
import TransportProcess from '@/components/TransportProcess.vue'
import TransportProcessGroup from '@/components/TransportProcessGroup.vue'

const store = TransportModal()
const transportStore = Transports()

const waterTrucks = WaterTrucks()

const envSettings = settings

const redSlides = reactive([
   {
      bgColor: 'stroke-red-500',
      textColor: 'text-red-400',
      timer: 30,
      value: computed(() => transportStore.isUnknown?.length),
      component: 'TruckIcon',
      componentParams: { width: 22, color: "fill-red-400", colorSecond: "fill-red-900" },
   },
])
</script>