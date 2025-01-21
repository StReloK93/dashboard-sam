<template>
   <main class="border-r border-zinc-800 flex flex-col">
      <TransitionGroup name="fade">
         <ReportModal @close="reportModal = false" v-if="reportModal" />
         <ParkTableModal @close="parkModal = false" v-if="parkModal" />
         <ParkTableExcavatorModal @close="parkExcavatorModal = false" v-if="parkExcavatorModal" />
         <ParkTableDrillingModal @close="parkDrillingModal = false" v-if="parkDrillingModal" />
      </TransitionGroup>
      <div class="xl:h-28 h-24 px-2 flex items-center justify-around relative">
         <ColumnTopSlider :slides="greySlides" />
         <main v-if="setting.tos" class="absolute top-1 right-1 flex flex-col gap-y-1 content-start justify-start">
            <button @click="parkModal = true"
               class="technical">
               TXK <TruckIcon color="fill-orange-400" width="20" colorSecond="fill-orange-700" class="-scale-x-100 ml-1.5 xl:w-[18px] w-[14px]"/>
            </button>
            <button @click="parkExcavatorModal = true"
               class="technical">
               TXK <img src="/images/excavator.png" class="xl:w-4 w-[14px] xl:ml-2 ml-1">
            </button>
            <button @click="parkDrillingModal = true"
               class="technical">
               TXK <img src="/images/drilling-rig.png" class="xl:w-4 w-[14px] xl:ml-2 ml-1">
            </button>
         </main>
      </div>
      <aside class="gray-scroll overflow-y-auto flex-grow scroll overflow-x-hidden relative">
         <TransportProcess counter="reys" :data="[]" grid-cols="grid-cols-3" color="yellow" class="opacity-0" />
         <main class="absolute inset-0">
            <TransportProcessGroup @openModal="(transport) => store.openModal(5, transport)" grid-cols="grid-cols-3" :title="$t('inatb')" color="gray"
               scroll-color="gray-scroll" :data="transportStore.inATBGroup" />
         </main>
      </aside>
   </main>
</template>

<script setup lang="ts">
import { reactive, computed, ref } from "vue";
import ParkTableModal from "@/components/ParkTableModal.vue";
import ParkTableExcavatorModal from "@/components/ParkTableExcavatorModal.vue";
import ParkTableDrillingModal from "@/components/ParkTableDrillingModal.vue";
import ColumnTopSlider from "@/components/ColumnTopSlider.vue";
import { Transports, TransportModal } from "@/entities/transports";
import TransportProcess from "@/components/TransportProcess.vue";
import ReportModal from "@/components/ReportModal.vue";
import TransportProcessGroup from '@/components/TransportProcessGroup.vue'

const parkModal = ref(false);
const parkExcavatorModal = ref(false);
const parkDrillingModal = ref(false);

const store = TransportModal();
const transportStore = Transports();

const setting = settings

const reportModal = ref(false);
const greySlides: any = reactive([
   {
      bgColor: "stroke-gray-400",
      textColor: "text-gray-400",
      timer: 30,
      value: computed(() => transportStore.inATB?.length),
      component: "TruckIcon",
      componentParams: {
         width: 22,
         color: "fill-gray-400",
         colorSecond: "fill-gray-700",
      },
   },
   {
      onClick: () => (reportModal.value = true),
      bgColor: "stroke-gray-400",
      textColor: "text-gray-400",
      class: "hover:bg-zinc-800 cursor-pointer active:bg-zinc-900",
      timer: 30,
      value: "Hisobot",
      icon: "fa-duotone fa-scroll",
   },
]);
</script>
