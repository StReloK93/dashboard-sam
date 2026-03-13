<template>
   <main class="border-r border-zinc-950 flex flex-col">
      <div class="flex items-center justify-center gap-3 p-3">
         <IndicatorButton :slides="greySlides" />
      </div>
      <aside
         class="gray-scroll overflow-y-auto flex-grow scroll overflow-x-hidden relative"
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
               @openModal="(transport: any) => store.openModal(5, transport)"
               grid-cols="grid-cols-3"
               :title="$t('inatb')"
               color="gray"
               scroll-color="gray-scroll"
               :data="transportStore.inATBGroup"
            />
         </main>
      </aside>
   </main>
</template>

<script setup lang="ts">
import IndicatorButton from "@/ui/IndicatorButton.vue";
import { reactive, computed, ref } from "vue";
import { Transports, TransportModal } from "@/entities/transports";
import TransportProcess from "@/components/TransportProcess.vue";
import TransportProcessGroup from "@/components/TransportProcessGroup.vue";

const store = TransportModal();
const transportStore = Transports();

const greySlides: any = reactive([
   {
      textColor: "text-gray-300",
      value: computed(() => {
         var summa = 0;
         for (const key in transportStore.inATBGroup) {
            summa += transportStore.inATBGroup[key].cars.length;
         }
         return summa;
      }),
      component: "TruckIcon",
      componentParams: {
         width: 20,
         color: "fill-gray-400",
         colorSecond: "fill-gray-500",
      },
   },
]);
</script>
