<template>
   <main class="border-r border-zinc-950 flex flex-col">
      <div class="flex items-center justify-center gap-3 p-3">
         <!-- <IndicatorButton :slides="slides" /> -->
         <ColumnTopSlider :slides="slides" />
      </div>
      <aside
         class="red-scroll overflow-y-auto flex-grow scroll overflow-x-hidden relative"
      >
         <TransportProcess
            counter="reys"
            :data="[]"
            grid-cols="lg:grid-cols-3 grid-cols-2"
            color="yellow"
            class="opacity-0"
         />
         <main class="absolute inset-0">
            <TransportProcess
               counter="timer"
               grid-cols="lg:grid-cols-3 grid-cols-2"
               @openModal="(transport: any) => store.openModal(4, transport)"
               :title="$t('inred')"
               color="red"
               :data="transportStore.isUnknown"
            />
            <hr v-if="envSettings.gusaks" class="my-2 border-zinc-950" />
            <TransportProcessGroup
               v-if="envSettings.gusaks"
               grid-cols="lg:grid-cols-3 grid-cols-2"
               :title="$t('ingusak')"
               scroll-color="sky-scroll"
               color="sky"
               :data="waterTrucksStore.inGUSAK"
            />
         </main>
      </aside>
   </main>
</template>

<script setup lang="ts">
import { reactive, computed } from "vue";
import IndicatorButton from "@/ui/IndicatorButton.vue";
import {
   Transports,
   TransportModal,
   useWaterTrucks,
} from "@/entities/transports";
import TransportProcess from "@/components/TransportProcess.vue";
import TransportProcessGroup from "@/components/TransportProcessGroup.vue";
import ColumnTopSlider from "@/components/ColumnTopSlider.vue";

const store = TransportModal();
const transportStore = Transports();

const waterTrucksStore = useWaterTrucks();

const envSettings = settings;

const slides = reactive([
   {
      bgColor: "stroke-red-500",
      textColor: "text-red-400",
      timer: 30,
      value: computed(() => transportStore.isUnknown?.length),
      component: "TruckIcon",
      componentParams: {
         width: 20,
         color: "fill-red-400",
         colorSecond: "fill-red-900",
      },
   },
]);
</script>
