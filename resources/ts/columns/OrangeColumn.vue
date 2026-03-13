<template>
   <main class="border-r border-zinc-950 flex flex-col">
      <Transition name="fade">
         <PricingMasters
            @close="pricingModal = false"
            color="orange"
            v-if="pricingModal"
         />
      </Transition>
      <div class="flex items-center justify-center gap-3 p-3">
         <IndicatorButton :slides="slides" />
      </div>
      <aside
         class="orange-scroll overflow-y-auto flex-grow scroll overflow-x-hidden relative"
      >
         <TransportProcess
            counter="reys"
            :data="[]"
            :grid-cols="`lg:grid-cols-3 grid-cols-2`"
            color="yellow"
            class="opacity-0"
         />
         <main class="absolute inset-0">
            <TransportProcessGroup
               :grid-cols="`lg:grid-cols-3 grid-cols-2`"
               @openModal="(transport: any) => store.openModal(2, transport)"
               :title="$t('inoil')"
               color="orange"
               scroll-color="orange-scroll"
               :data="transportStore.inOIL"
            />
            <hr v-if="setting.BASE_DPP?.length" class="my-2 border-zinc-800" />
            <TransportProcessGroup
               v-if="setting.BASE_DPP?.length"
               :grid-cols="`lg:grid-cols-3 grid-cols-2`"
               @openModal="(transport: any) => store.openModal(2, transport)"
               :title="$t('inspt')"
               color="orange"
               scroll-color="orange-scroll"
               :data="transportStore.inDPP"
            />
         </main>
      </aside>
   </main>
</template>

<script setup lang="ts">
import { ref, reactive, computed } from "vue";
import { Transports, TransportModal } from "@/entities/transports";
import PricingMasters from "@/components/PricingMasters.vue";
import IndicatorButton from "@/ui/IndicatorButton.vue";
import TransportProcessGroup from "@/components/TransportProcessGroup.vue";
import TransportProcess from "@/components/TransportProcess.vue";

const store = TransportModal();
const transportStore = Transports();

const pricingModal = ref(false);

const setting = settings;

const slides = reactive([
   {
      textColor: "text-orange-400",
      value: computed(() => {
         var summa = 0;
         for (const key in transportStore.inOIL) {
            summa += transportStore.inOIL[key].cars.length;
         }
         return summa;
      }),
      component: "TruckIcon",
      componentParams: {
         width: 20,
         color: "fill-orange-500",
         colorSecond: "fill-orange-900",
      },
   },
   {
      textColor: "text-orange-400",
      value: computed(() => transportStore.statesSumm.summOilTime),
      icon: "fa-duotone fa-hourglass-clock",
   },
   {
      textColor: "text-orange-400",
      value: computed(() => transportStore.summaOilCars),
      icon: "fa-solid fa-layer-group",
   },
   {
      onClick: () => (pricingModal.value = true),
      textColor: "text-orange-400",
      icon: "fa-duotone fa-chart-simple",
   },
]);
</script>
