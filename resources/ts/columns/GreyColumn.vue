<template>
   <main class="border-r border-zinc-800 flex flex-col">
      <TransitionGroup name="fade">
         <ReportModal @close="reportModal = false" v-if="reportModal" />
         <ParkTableModal @close="parkModal = false" v-if="parkModal" />
         <ParkTableExcavatorModal @close="parkExcavatorModal = false" v-if="parkExcavatorModal" />
      </TransitionGroup>
      <div class="h-28 px-2 flex items-center justify-around relative">
         <ColumnTopSlider :slides="greySlides" />
         <main class="absolute top-1 right-1 flex flex-col">
            <button @click="parkModal = true"
               class="border-2 flex border-gray-400 text-gray-300 font-semibold py-1 px-2 shadow shadow-gray-600 rounded text-sm mb-1">
               TXK <TruckIcon color="fill-orange-400" width="20" colorSecond="fill-orange-700" class="-scale-x-100 ml-1.5"/>
            </button>
            <button @click="parkExcavatorModal = true"
               class="border-2 flex border-gray-400 text-gray-300 font-semibold py-1 px-2 shadow shadow-gray-600 rounded text-sm">
               TXK <img src="/images/excavator.png" class="w-4  ml-2">
            </button>
         </main>
      </div>
      <aside class="gray-scroll overflow-y-auto flex-grow scroll overflow-x-hidden relative">
         <TransportProcess counter="reys" :data="[]" grid-cols="grid-cols-3" color="yellow" class="opacity-0" />
         <main class="absolute inset-0">
            <TransportProcessGroup @openModal="(transport) => store.openModal(5, transport)" grid-cols="grid-cols-3" title="ATB maydonida" color="gray"
               scroll-color="gray-scroll" :data="transportStore.inATBGroup" />
         </main>
      </aside>
   </main>
</template>

<script setup lang="ts">
import { reactive, computed, ref } from "vue";
import ParkTableModal from "@/components/ParkTableModal.vue";
import ParkTableExcavatorModal from "@/components/ParkTableExcavatorModal.vue";
import ColumnTopSlider from "@/components/ColumnTopSlider.vue";
import { Transports, TransportModal } from "@/entities/transports";
import TransportProcess from "@/components/TransportProcess.vue";
import ReportModal from "@/components/ReportModal.vue";
import TransportProcessGroup from '@/components/TransportProcessGroup.vue'

const parkModal = ref(false);
const parkExcavatorModal = ref(false);

const store = TransportModal();
const transportStore = Transports();

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
