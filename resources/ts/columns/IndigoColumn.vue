<template>
   <main class="border-r border-zinc-800 flex flex-col">
      <div class="h-28 px-2 flex items-center justify-around">
         <aside>
            <Swiper :loop="true" effect="cards" class="w-[90px] h-[92px]" :slides-per-view="1" :slides-per-group="1"
               :modules="[EffectCards]">
               <Slide class="!w-[90px]">
                  <CircleUI :timer="30" bgColor="stroke-indigo-500" textColor="text-indigo-400"
                     :summa="transportStore.inSmenaAll?.length">
                     <TruckIcon width="22" color="fill-indigo-400" colorSecond="fill-indigo-900"
                        class="-scale-x-100 translate-y-1.5" />
                  </CircleUI>

               </Slide>
               <Slide class="!w-[90px]">
                  <CircleUI :timer="30" bgColor="stroke-indigo-400" textColor="text-indigo-400" type="time"
                     :summa="transportStore.statesSumm.summSmenaTime">
                     <i class="fa-duotone fa-hourglass-clock"></i>
                  </CircleUI>
               </Slide>

               <Slide class="!w-[90px]">
                  <CircleUI :timer="30" bgColor="stroke-indigo-400" textColor="text-indigo-400"
                     :summa="transportStore.summaSmenaCars">
                     <i class="fa-solid fa-layer-group"></i>
                  </CircleUI>
               </Slide>
            </Swiper>
         </aside>
      </div>
      <aside class="green-scroll overflow-y-auto flex-grow scroll overflow-x-hidden">
         <TransportProcessGroup 
               grid-cols="grid-cols-3" 
               @openModal="(transport) => store.openModal(3, transport)"
               title="Smena almashish maydonida" 
               color="indigo" 
               scroll-color="indigo-scroll"
               :data="transportStore.inSMENA"
            />
      </aside>
   </main>
</template>

<script setup lang="ts">
import { Transports, TransportModal } from '@/entities/transports'
import { EffectCards, Autoplay } from 'swiper/modules'
import { ref } from 'vue'

import { SwiperSlide as Slide, Swiper } from 'swiper/vue'
import TransportProcessGroup from '@/components/TransportProcessGroup.vue'
import CircleUI from '@/ui/CircleUI.vue'
import TruckIcon from '@/ui/TruckIcon.vue'

const store = TransportModal()
const transportStore = Transports()


const pricingModal = ref(false)
</script>