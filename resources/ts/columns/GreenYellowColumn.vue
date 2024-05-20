<template>
   <main class="border-r border-zinc-800 flex flex-col">
      <div class="h-28 px-2 flex items-center justify-around">
         <!-- <ColumnTopSlider v-model:slides="slides" /> -->
         <aside>
            <Swiper :loop="true" effect="cards" class="w-[90px] h-[92px]" :slides-per-view="1" :slides-per-group="1"
               :modules="[EffectCards]">
               <Slide class="!w-[90px]">
                  <CircleUI @start="transportStore.getTransports()" :timer="30" bgColor="stroke-green-600"
                     textColor="text-green-400" :summa="transportStore.inProcess?.length">
                     <TruckIcon width="22" color="fill-green-500" colorSecond="fill-green-900"
                        class="-scale-x-100 translate-y-1.5" />
                  </CircleUI>
               </Slide>
               <Slide class="!w-[90px]">
                  <CircleUI :timer="30" bgColor="stroke-green-400" textColor="text-green-400"
                     :summa="transportStore.statesSumm.reysCount">
                     <i class="fa-regular fa-sigma"></i>
                  </CircleUI>
               </Slide>
            </Swiper>
         </aside>
         <aside>
            <Swiper :loop="true" effect="cards" class="w-[90px] h-[92px]" :slides-per-view="1" :slides-per-group="1"
               :modules="[EffectCards]">
               <Slide class="!w-[90px]">
                  <CircleUI :timer="30" bgColor="stroke-yellow-600" textColor="text-yellow-400"
                     :summa="transportStore.inExcavator?.length">
                     <TruckIcon width="22" color="fill-yellow-500" colorSecond="fill-yellow-900"
                        class="-scale-x-100 translate-y-1.5" />
                  </CircleUI>
               </Slide>
               <Slide class="!w-[90px]">
                  <CircleUI :timer="30" bgColor="stroke-yellow-400" textColor="text-yellow-400" type="time"
                     :summa="transportStore.statesSumm.summExcavatorTime">
                     <i class="fa-duotone fa-hourglass-clock"></i>
                  </CircleUI>
               </Slide>
            </Swiper>
         </aside>
      </div>
      <aside class="green-scroll overflow-y-auto flex-grow scroll overflow-x-hidden">
         <TransportProcess counter="reys" :data="transportStore.inProcess" grid-cols="grid-cols-4"
            @openModal="(transport) => store.openModal(0, transport)" title="Ish jarayonida" color="green" />
         <TransportProcess counter="timer" :data="transportStore.inExcavator" grid-cols="grid-cols-4"
            @openModal="(transport) => store.openModal(1, transport)" title="Kutish jarayonida" color="yellow" />
      </aside>
   </main>
</template>

<script setup lang="ts">
import ColumnTopSlider from '@/components/ColumnTopSlider.vue'
import { Transports, TransportModal } from '@/entities/transports'
import { EffectCards, Autoplay } from 'swiper/modules'
import { ref } from 'vue'
import { SwiperSlide as Slide, Swiper } from 'swiper/vue'
import TransportProcess from '@/components/TransportProcess.vue'
import CircleUI from '@/ui/CircleUI.vue'
import TruckIcon from '@/ui/TruckIcon.vue'

const store = TransportModal()
const transportStore = Transports()


// const slides = ref([
//    {
//       start: null,
//       click: null,
//       bgColor: 'stroke-green-600',
//       textColor: 'text-green-400',
//       class: null,
//       timer: 30,
//       component: 'TruckIcon',
//       value: transportStore.inProcess.length,
//       componentParams: { width: 22, color: "fill-green-500", colorSecond: "fill-green-900", class: "-scale-x-100 translate-y-1.5" },
//    }
// ])
</script>