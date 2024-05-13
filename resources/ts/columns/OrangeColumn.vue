<template>
   <main class="border-r border-zinc-800 flex flex-col">
      <Transition name="fade">
         <PricingMasters @close="pricingModal = false" color="orange" v-if="pricingModal" />
      </Transition>
      <div class="h-28 px-2 flex items-center justify-around">
         <aside>
            <Swiper :loop="true" effect="cards" class="w-[90px] h-[92px]" :slides-per-view="1" :slides-per-group="1"
               :modules="[EffectCards]">
               <Slide class="!w-[90px]">
                  <CircleUI :timer="30" bgColor="stroke-orange-500" textColor="text-orange-400"
                     :summa="transportStore.inOilAll?.length">
                     <TruckIcon width="22" color="fill-orange-600" colorSecond="fill-orange-900"
                        class="-scale-x-100 translate-y-1.5" />
                  </CircleUI>
               </Slide>
               <Slide class="!w-[90px]">
                  <CircleUI :timer="30" bgColor="stroke-orange-400" textColor="text-orange-400" type="time"
                     :summa="transportStore.statesSumm.summOilTime">
                     <i class="fa-duotone fa-hourglass-clock"></i>
                  </CircleUI>
               </Slide>
               <Slide class="!w-[90px]">
                  <CircleUI :timer="30" bgColor="stroke-orange-400" textColor="text-orange-400"
                     :summa="transportStore.summaOilCars">
                     <i class="fa-solid fa-layer-group"></i>
                  </CircleUI>
               </Slide>
               <Slide class="!w-[90px]">
                  <CircleUI 
                     :timer="30" 
                     bgColor="stroke-orange-400"
                     summa="Baholash"
                     textColor="text-orange-400"
                     @click="pricingModal = true" 
                     class="hover:bg-zinc-800 cursor-pointer active:bg-zinc-900"
                  >
                  <i class="fa-duotone fa-chart-simple"></i>
                  </CircleUI>
               </Slide>
            </Swiper>
         </aside>
      </div>
      <aside class="green-scroll overflow-y-auto flex-grow scroll overflow-x-hidden">
         <TransportProcessGroup grid-cols="grid-cols-3" @openModal="(transport) => store.openModal(2, transport)"
            title="Yoqilg'i olish maydonida" color="orange" scroll-color="orange-scroll" :data="transportStore.inOIL" />
      </aside>
   </main>
</template>

<script setup lang="ts">
import { Transports, TransportModal } from '@/entities/transports'
import { EffectCards, Autoplay } from 'swiper/modules'
import PricingMasters from '@/components/PricingMasters.vue'
import { ref } from 'vue'

import { SwiperSlide as Slide, Swiper } from 'swiper/vue'
import TransportProcessGroup from '@/components/TransportProcessGroup.vue'
import CircleUI from '@/ui/CircleUI.vue'
import TruckIcon from '@/ui/TruckIcon.vue'

const store = TransportModal()
const transportStore = Transports()


const pricingModal = ref(false)
</script>