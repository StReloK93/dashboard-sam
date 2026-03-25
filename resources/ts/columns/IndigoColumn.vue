<template>
   <main class="border-r border-zinc-950 flex flex-col">
      <div class="flex items-center justify-center gap-3 p-3">
         <ColumnTopSlider :slides="slides" />
      </div>
      <aside
         class="indigo-scroll overflow-y-auto flex-grow scroll overflow-x-hidden relative"
      >
         <TransportProcess
            counter="reys"
            :data="[]"
            grid-cols="grid-cols-3"
            color="yellow"
            class="opacity-0"
         />
         <main class="absolute inset-0">
            <TransportProcessGroup
               v-if="setting.BASE_REMONT?.length"
               grid-cols="grid-cols-3"
               @openModal="(transport: any) => store.openModal(3, transport)"
               :title="$t('inremont')"
               color="indigo"
               scroll-color="indigo-scroll"
               :data="transportStore.inREMONT"
            />
            <hr
               v-if="setting.BASE_REMONT?.length"
               class="my-2 border-zinc-800"
            />
            <TransportProcessGroup
               grid-cols="grid-cols-3"
               @openModal="(transport: any) => store.openModal(3, transport)"
               :title="$t('inchange')"
               color="indigo"
               scroll-color="indigo-scroll"
               :data="transportStore.inSMENA"
            />
         </main>
      </aside>
   </main>
</template>

<script setup lang="ts">
import { Transports, TransportModal } from "@/entities/transports";
import IndicatorButton from "@/ui/IndicatorButton.vue";
import TransportProcess from "@/components/TransportProcess.vue";
import { reactive, computed } from "vue";
import TransportProcessGroup from "@/components/TransportProcessGroup.vue";
import { minuteFormat } from "@/helpers/timeFormat";
import ColumnTopSlider from "@/components/ColumnTopSlider.vue";

const setting = settings;
const store = TransportModal();
const transportStore = Transports();

const slides = reactive([
   {
      bgColor: "stroke-indigo-500",
      textColor: "text-indigo-400",
      timer: 30,
      value: computed(() => {
         var summa = 0;
         for (const key in transportStore.inSMENA) {
            summa += transportStore.inSMENA[key].cars.length;
         }

         for (const key in transportStore.inREMONT) {
            summa += transportStore.inREMONT[key].cars.length;
         }
         return summa;
      }),
      component: "TruckIcon",
      componentParams: {
         width: 20,
         color: "fill-indigo-400",
         colorSecond: "fill-indigo-900",
      },
   },
   {
      textColor: "text-indigo-400",
      value: computed(() =>
         minuteFormat(transportStore.statesSumm.summSmenaTime),
      ),
      icon: "fa-duotone fa-hourglass-clock",
   },
   {
      textColor: "text-indigo-400",
      value: computed(() => transportStore.summaSmenaCars),
      icon: "fa-solid fa-layer-group",
   },
]);
</script>
