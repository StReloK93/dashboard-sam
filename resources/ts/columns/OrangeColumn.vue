<template>
   <main class="border-r border-zinc-800 flex flex-col">
      <Transition name="fade">
         <PricingMasters
            @close="pricingModal = false"
            color="orange"
            v-if="pricingModal"
         />
      </Transition>
      <div class="xl:h-24 h-[72px] flex items-center justify-around">
         <ColumnTopSlider :slides="orangeSlides" />
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
               @openModal="(transport) => store.openModal(2, transport)"
               :title="$t('inoil')"
               color="orange"
               scroll-color="orange-scroll"
               :data="transportStore.inOIL"
            />
            <hr v-if="setting.BASE_DPP?.length" class="my-2 border-zinc-800" />
            <TransportProcessGroup
               v-if="setting.BASE_DPP?.length"
               :grid-cols="`lg:grid-cols-3 grid-cols-2`"
               @openModal="(transport) => store.openModal(2, transport)"
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
import ColumnTopSlider from "@/components/ColumnTopSlider.vue";
import TransportProcessGroup from "@/components/TransportProcessGroup.vue";
import TransportProcess from "@/components/TransportProcess.vue";

const store = TransportModal();
const transportStore = Transports();

const pricingModal = ref(false);

const setting = settings;

const orangeSlides = reactive([
   {
      bgColor: "stroke-orange-500",
      textColor: "text-orange-400",
      timer: 30,
      value: computed(() => {
         var summa = 0;
         for (const key in transportStore.inOIL) {
            summa += transportStore.inOIL[key].cars.length;
         }
         return summa;
      }),
      component: "TruckIcon",
      componentParams: {
         width: 22,
         color: "fill-orange-500",
         colorSecond: "fill-orange-900",
      },
   },
   {
      bgColor: "stroke-orange-400",
      textColor: "text-orange-400",
      timer: 30,
      type: "time",
      value: computed(() => transportStore.statesSumm.summOilTime),
      icon: "fa-duotone fa-hourglass-clock",
   },
   {
      bgColor: "stroke-orange-400",
      textColor: "text-orange-400",
      timer: 30,
      value: computed(() => transportStore.summaOilCars),
      icon: "fa-solid fa-layer-group",
   },
   {
      onClick: () => (pricingModal.value = true),
      bgColor: "stroke-orange-400",
      textColor: "text-orange-400",
      timer: 30,
      value: "Baholash",
      icon: "fa-duotone fa-chart-simple",
      class: "hover:bg-zinc-800 cursor-pointer active:bg-zinc-900",
   },
]);
</script>
