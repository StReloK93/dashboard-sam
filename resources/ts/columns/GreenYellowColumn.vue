<template>
   <main
      class="border-r border-zinc-950 flex flex-col xl:flex-grow-0 flex-grow"
   >
      <aside class="flex justify-between">
         <div class="flex gap-3 p-3 justify-center flex-wrap">
            <IndicatorButton :slides="greenSlides" />
         </div>
         <div class="flex gap-3 p-3 justify-center flex-wrap">
            <IndicatorButton :slides="yellowSlides" />
         </div>
      </aside>
      <aside
         class="green-scroll overflow-y-auto flex-grow scroll overflow-x-hidden relative grid-col"
      >
         <TransportProcess
            counter="reys"
            :data="[]"
            :grid-cols="`xl:grid-cols-5 lg:grid-cols-5 grid-cols-3`"
            color="yellow"
            class="opacity-0"
         />
         <main class="absolute inset-0">
            <TransportProcess
               counter="reys"
               :data="transportStore.inProcess"
               :grid-cols="`xl:grid-cols-5 lg:grid-cols-5 grid-cols-3`"
               @openModal="(transport: any) => store.openModal(0, transport)"
               :title="$t('inprocess')"
               color="green"
            />
            <TransportProcess
               counter="timer"
               :data="transportStore.inExcavator"
               :grid-cols="`xl:grid-cols-5 lg:grid-cols-5 grid-cols-3`"
               @openModal="(transport: any) => store.openModal(1, transport)"
               :title="$t('inexcavator')"
               color="yellow"
            />
         </main>
      </aside>
   </main>
</template>

<script setup lang="ts">
import IndicatorButton from "@/ui/IndicatorButton.vue";
import TransportProcess from "@/components/TransportProcess.vue";
import { Transports, TransportModal } from "@/entities/transports";
import { reactive, computed } from "vue";
import { minuteFormat } from "@/helpers/timeFormat";

const store = TransportModal();
const transportStore = Transports();

const greenSlides = reactive([
   {
      bgColor: "stroke-green-600",
      textColor: "text-green-400",
      timer: 30,
      value: computed(() => transportStore.inProcess?.length),
      component: "TruckIcon",
      componentParams: {
         width: 20,
         color: "fill-green-500",
         colorSecond: "fill-green-600",
      },
   },
   {
      bgColor: "stroke-green-400",
      textColor: "text-green-400",
      timer: 30,
      icon: "fa-regular fa-sigma",
      value: computed(() => transportStore.statesSumm.reysCount),
   },
]);

const yellowSlides = reactive([
   {
      textColor: "text-yellow-400",
      value: computed(() => transportStore.inExcavator?.length),
      component: "TruckIcon",
      componentParams: {
         width: 20,
         color: "fill-yellow-500",
         colorSecond: "fill-yellow-600",
      },
   },
   {
      textColor: "text-yellow-400",
      icon: "fa-duotone fa-hourglass-clock",
      value: computed(() =>
         minuteFormat(transportStore.statesSumm.summExcavatorTime),
      ),
   },
]);
</script>
