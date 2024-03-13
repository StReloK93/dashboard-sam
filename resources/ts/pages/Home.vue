<template>
   <section class="flex h-full">
      <!-- Process ish jarayonida -->
      <GeneralColumn :duo="true" scroll-color="green-scroll">
         <template v-slot:firstSlider>
            <Slide class="!w-[90px]">
               <CircleUI bgColor="stroke-green-600" textColor="text-green-400" :summa="transportStore.inProcess?.length">
                  <TruckIcon width="22" color="fill-green-500" colorSecond="fill-green-900" class="-scale-x-100 translate-y-1.5"/>
               </CircleUI>
            </Slide>
            <Slide class="!w-[90px]">
               <CircleUI bgColor="stroke-green-400" textColor="text-green-400" :summa="transportStore.statesSumm.reysCount">
                  <i class="fa-regular fa-sigma"></i>
               </CircleUI>
            </Slide>
         </template>
         <template v-slot:secondSlider>
            <Slide class="!w-[90px]">
               <CircleUI bgColor="stroke-yellow-600" textColor="text-yellow-400" :summa="transportStore.inExcavator?.length">
                  <TruckIcon width="22" color="fill-yellow-500" colorSecond="fill-yellow-900" class="-scale-x-100 translate-y-1.5"/>
               </CircleUI>
            </Slide>
            <Slide class="!w-[90px]">
               <CircleUI bgColor="stroke-yellow-400" textColor="text-yellow-400" type="time" :summa="transportStore.statesSumm.summExcavatorTime">
                  <i class="fa-duotone fa-hourglass-clock"></i>
               </CircleUI>
            </Slide>
         </template>
         <template v-slot:firstProcess>
            <TransportProcess
               counter="reys"
               :data="transportStore.inProcess"
               grid-cols="grid-cols-4"
               @openModal="(transport) => store.openModal(0, transport)" 
               title="Ish jarayonida"
               color="green"
            />
         </template>
         <template v-slot:secondProcess>
            <TransportProcess 
               counter="timer" 
               :data="transportStore.inExcavator" 
               grid-cols="grid-cols-4"
               @openModal="(transport) => store.openModal(1, transport)" 
               title="Kutish jarayonida" 
               color="yellow"
            />
         </template>
      </GeneralColumn>
   
      <!-- yoqilgi olish jarayonida -->
      <GeneralColumn scroll-color="orange-scroll">
         <template v-slot:firstSlider>
            <Slide class="!w-[90px]">
               <CircleUI bgColor="stroke-orange-500" textColor="text-orange-400" :summa="transportStore.inOilAll?.length">
                  <TruckIcon width="22" color="fill-orange-600" colorSecond="fill-orange-900" class="-scale-x-100 translate-y-1.5"/>
               </CircleUI>
            </Slide>
            <Slide class="!w-[90px]">
               <CircleUI bgColor="stroke-orange-400" textColor="text-orange-400" type="time" :summa="transportStore.statesSumm.summOilTime">
                  <i class="fa-duotone fa-hourglass-clock"></i>
               </CircleUI>
            </Slide>
            <Slide class="!w-[90px]">
               <CircleUI bgColor="stroke-orange-400" textColor="text-orange-400" :summa="transportStore.summaOilCars">
                  <i class="fa-solid fa-layer-group"></i>
               </CircleUI>
            </Slide>
         </template>
         <template v-slot:firstProcess>
            <TransportProcessGroup 
               grid-cols="grid-cols-3" 
               @openModal="(transport) => store.openModal(2, transport)"
               title="Yoqilg'i olish maydonida" 
               color="orange"
               scroll-color="orange-scroll"
               :data="transportStore.inOIL"
            />
         </template>
      </GeneralColumn>
   
      <!-- Binafsha Smena almashish -->
      <GeneralColumn>
         <template v-slot:firstSlider>
            <Slide class="!w-[90px]">
               <CircleUI bgColor="stroke-indigo-500" textColor="text-indigo-400" :summa="transportStore.inSmenaAll?.length">
                  <TruckIcon width="22" color="fill-indigo-400" colorSecond="fill-indigo-900" class="-scale-x-100 translate-y-1.5"/>
               </CircleUI>

            </Slide>
            <Slide class="!w-[90px]">
               <CircleUI bgColor="stroke-indigo-400" textColor="text-indigo-400" type="time" :summa="transportStore.statesSumm.summSmenaTime">
                  <i class="fa-duotone fa-hourglass-clock"></i>
               </CircleUI>
            </Slide>

            <Slide class="!w-[90px]">
               <CircleUI bgColor="stroke-indigo-400" textColor="text-indigo-400" :summa="transportStore.summaSmenaCars">
                  <i class="fa-solid fa-layer-group"></i>
               </CircleUI>
            </Slide>
            
         </template>
         <template v-slot:firstProcess>
            <TransportProcessGroup 
               grid-cols="grid-cols-3" 
               @openModal="(transport) => store.openModal(3, transport)"
               title="Smena almashish maydonida" 
               color="indigo" 
               scroll-color="indigo-scroll"
               :data="transportStore.inSMENA"
            />
         </template>
      </GeneralColumn>
   
      <!-- Qizil noaniq -->
      <GeneralColumn scrollColor="red-scroll">
         <template v-slot:firstSlider>
            <Slide class="!w-[90px]">
               <CircleUI bgColor="stroke-red-500" textColor="text-red-400" :summa="transportStore.isUnknown?.length">
                  <TruckIcon width="22" color="fill-red-400" colorSecond="fill-red-900" class="-scale-x-100 translate-y-1.5"/>
               </CircleUI>
            </Slide>
         </template>
   
         <template v-slot:firstProcess>
            <TransportProcess
               counter="timer"
               grid-cols="grid-cols-2" 
               @openModal="(transport) => store.openModal(4, transport)"
               title="Sababsiz to'xtaganlar" 
               color="red"
               
               :data="transportStore.isUnknown"
            />
         </template>
      </GeneralColumn>
   
      <!-- Kulrang ATB da  -->
      <GeneralColumn scroll-color="gray-scroll">
         <template v-slot:firstSlider>
            <Slide class="!w-[90px]">
               <CircleUI bgColor="stroke-gray-400" textColor="text-gray-400" :summa="transportStore.inATB?.length">
                  <TruckIcon width="22" color="fill-gray-400" colorSecond="fill-gray-700" class="-scale-x-100 translate-y-1.5"/>
               </CircleUI>
            </Slide>
         </template>
   
         <template v-slot:firstProcess>
            <TransportProcess
               counter="timer"
               grid-cols="grid-cols-3"
               @openModal="(transport) => store.openModal(5, transport)"
               title="ATB maydonida" 
               color="gray" 
               :data="transportStore.inATB"
            />
         </template>
      </GeneralColumn>
   
      <ChartColumn />
      <transition name="fade">
         <SingleTransport class="z-50" v-if="store.transport" />
      </transition>
   </section>
</template>

<script setup lang="ts">
import SingleTransport from '@/components/TransportModal.vue'
import TransportProcessGroup from '@/components/TransportProcessGroup.vue'
import TransportProcess from '@/components/TransportProcess.vue'
import GeneralColumn from '@/columns/GeneralColumn.vue'
import ChartColumn from '@/columns/ChartColumn.vue'
import CircleUI from '@/ui/CircleUI.vue'
import { Transports , TransportModal } from '@/entities/transports'
import { SwiperSlide as Slide } from 'swiper/vue'
import TruckIcon from '@/ui/TruckIcon.vue'
const store = TransportModal()
const transportStore = Transports()
</script>