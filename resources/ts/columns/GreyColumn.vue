<template>
   <main class="border-r border-zinc-800 flex flex-col">
      <Transition name="fade">
         <ReportModal @close="reportModal = false" v-if="reportModal" />
      </Transition>
      <div class="h-28 px-2 flex items-center justify-around relative">
         <aside>
            <Swiper :loop="true" effect="cards" class="w-[90px] h-[92px]" :slides-per-view="1" :slides-per-group="1"
               :modules="[EffectCards]">
               <Slide class="!w-[90px]">
                  <CircleUI :timer="30" bgColor="stroke-gray-400" textColor="text-gray-400"
                     :summa="transportStore.inATB?.length">
                     <TruckIcon width="22" color="fill-gray-400" colorSecond="fill-gray-700"
                        class="-scale-x-100 translate-y-1.5" />
                  </CircleUI>
               </Slide>
               <Slide class="!w-[90px]">
                  <CircleUI
                     :timer="30"
                     bgColor="stroke-gray-400" textColor="text-gray-400"
                     summa="Hisobot"
                     @click="reportModal = true" 
                     class="hover:bg-zinc-800 cursor-pointer active:bg-zinc-900"
                  >
                     <i class="fa-duotone fa-scroll"></i>
                  </CircleUI>
               </Slide>
            </Swiper>
         </aside>
      </div>
      <aside class="green-scroll overflow-y-auto flex-grow scroll overflow-x-hidden">
         <TransportProcess counter="timer" grid-cols="grid-cols-3"
            @openModal="(transport) => store.openModal(5, transport)" title="ATB maydonida" color="gray"
            :data="transportStore.inATB" />
      </aside>
   </main>
</template>

<script setup lang="ts">

import { Transports, TransportModal } from '@/entities/transports'
import { EffectCards, Autoplay } from 'swiper/modules'
import { ref } from 'vue'
import { SwiperSlide as Slide, Swiper } from 'swiper/vue'
import TransportProcess from '@/components/TransportProcess.vue'
import ReportModal from '@/components/ReportModal.vue'
import CircleUI from '@/ui/CircleUI.vue'
import TruckIcon from '@/ui/TruckIcon.vue'

const store = TransportModal()
const transportStore = Transports()

const reportModal = ref(false)
</script>