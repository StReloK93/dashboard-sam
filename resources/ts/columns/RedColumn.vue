<template>
   <main class="border-r border-zinc-800 flex flex-col">
      <div class="h-28 px-2 flex items-center justify-around">
         <ColumnTopSlider :slides="redSlides" />
      </div>
      <aside class="red-scroll overflow-y-auto flex-grow scroll overflow-x-hidden">
         <TransportProcess counter="timer" grid-cols="grid-cols-2"
            @openModal="(transport) => store.openModal(4, transport)" title="Sababsiz to'xtaganlar" color="red"
            :data="transportStore.isUnknown" />
         <hr class="my-4 border-zinc-800">
         <TransportProcessGroup grid-cols="grid-cols-2" title="Suv olish maydonida" color="sky" scroll-color="sky-scroll" :data="waterTrucks.inGUSAK" />
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