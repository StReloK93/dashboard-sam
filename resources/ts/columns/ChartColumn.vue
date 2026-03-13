<template>
   <main ref="chartColumn" class="flex-grow relative min-w-60">
      <div
         class="absolute inset-0 overflow-y-auto scroll indigo-scroll overflow-hidden"
      >
         <div class="flex items-center gap-3 p-3">
            <a v-if="config.TRUCK_GRAPHIC" :href="config.TRUCK_GRAPHIC">
               <IndicatorButton
                  :slides="[
                     {
                        value: 'Smena tahlili',
                        textColor: 'text-gray-300',
                        icon: 'fa-duotone fa-solid fa-arrow-right-arrow-left relative top-px',
                     },
                  ]"
               />
            </a>
         </div>
         <ChartCircle
            v-if="transportState.DumpTrucks.length != 0"
            chartname="activeTrucks"
            :startcolor="'#01b0b0'"
            :endcolor="'#0198f7'"
            v-model="transportState.summaTransports"
         />
         <template v-if="config.excavators">
            <ChartCircle
               v-if="excavatorState.ExcavatorList.length != 0"
               chartname="activeExcavators"
               :startcolor="'#01b0b0'"
               :endcolor="'#2dd4bf'"
               v-model="excavatorState.informationProsent"
            />
         </template>

         <template v-if="config.pistali_mans">
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
import IndicatorButton from "@/ui/IndicatorButton.vue";
import ChartCircle from "@/components/ChartCircle.vue";
import { Transports } from "@/entities/transports";
import { Excavators } from "@/entities/transports/Excavators";
const transportState = Transports();
const excavatorState = Excavators();

const config = settings;
</script>
