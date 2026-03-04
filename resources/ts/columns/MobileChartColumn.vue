<template>
   <main
      ref="chartColumn"
      class="inner-shadow-second flex-grow relative min-w-60"
   >
      <div
         class="absolute inset-0 overflow-y-auto scroll indigo-scroll overflow-hidden flex items-center pr-12 py-4 bg-zinc-950"
      >
         <main class="absolute right-0 bottom-0 top-0 p-1 bg-zinc-950">
            <a
               v-if="settingCurrent.TRUCK_GRAPHIC"
               :href="settingCurrent.TRUCK_GRAPHIC"
            >
               <button
                  class="text-teal-400 text-sm bg-zinc-800 px-4 py-1 rounded active:scale-95"
               >
                  <i class="fa-duotone fa-solid fa-arrow-right-arrow-left"></i>
               </button>
            </a>
         </main>
         <!-- <ChartLine class="w-1/3" :label="false" /> -->
         <ChartCircle
            class="w-1/3"
            v-if="transportState.DumpTrucks.length != 0"
            chartname="activeTrucks"
            :startcolor="'#01b0b0'"
            :endcolor="'#0198f7'"
            v-model="transportState.summaTransports"
         />
         <template v-if="settingCurrent.excavators">
            <ChartCircle
               class="w-1/3"
               v-if="excavatorState.ExcavatorList.length != 0"
               chartname="activeExcavators"
               :startcolor="'#01b0b0'"
               :endcolor="'#2dd4bf'"
               v-model="excavatorState.informationProsent"
            />
         </template>

         <template v-if="settingCurrent.pistali_mans">
            <ChartCircle
               chartname="activeMans"
               :startcolor="'#01b0b0'"
               :endcolor="'#2dd4bf'"
               v-model="transportState.summaMans"
            />
         </template>
      </div>
   </main>
</template>
<script setup lang="ts">
import ChartCircle from "@/components/ChartCircle.vue";
import ChartLine from "@/components/ChartLine.vue";
import { Transports } from "@/entities/transports";
import { Excavators } from "@/entities/transports/Excavators";
const transportState = Transports();
const excavatorState = Excavators();

const settingCurrent = settings;
</script>
