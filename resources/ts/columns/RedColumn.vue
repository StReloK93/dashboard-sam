<template>
   <main class="border-r border-zinc-800 flex flex-col">
      <div class="h-28 px-2 flex items-center justify-around">
         <aside>
            <Swiper :loop="true" effect="cards" class="w-[90px] h-[92px]" :slides-per-view="1" :slides-per-group="1"
               :modules="[EffectCards]">
               <Slide class="!w-[90px]">
                  <CircleUI :timer="30" bgColor="stroke-red-500" textColor="text-red-400"
                     :summa="transportStore.isUnknown?.length">
                     <TruckIcon width="22" color="fill-red-400" colorSecond="fill-red-900"
                        class="-scale-x-100 translate-y-1.5" />
                  </CircleUI>
               </Slide>
            </Swiper>
         </aside>
      </div>
      <aside class="green-scroll overflow-y-auto flex-grow scroll overflow-x-hidden">
         <TransportProcess counter="timer" grid-cols="grid-cols-2"
            @openModal="(transport) => store.openModal(4, transport)" title="Sababsiz to'xtaganlar" color="red"
            :data="transportStore.isUnknown" />
      </aside>
   </main>
</template>

<script setup lang="ts">
import { Transports, TransportModal } from '@/entities/transports'
import { EffectCards, Autoplay } from 'swiper/modules'

import { SwiperSlide as Slide, Swiper } from 'swiper/vue'
import TransportProcess from '@/components/TransportProcess.vue'
import CircleUI from '@/ui/CircleUI.vue'
import TruckIcon from '@/ui/TruckIcon.vue'

const store = TransportModal()
const transportStore = Transports()

</script>