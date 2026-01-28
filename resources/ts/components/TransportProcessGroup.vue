<template>
   <section
      :class="props.scrollColor"
      class="xl:px-2 px-1 overflow-y-auto scroll relative"
   >
      <TransitionGroup name="fade">
         <TransportProcessGroupModal
            :color="props.color"
            v-if="timeLine && color != 'gray'"
            @close="timeLine = null"
            :group="timeLine"
         />
      </TransitionGroup>
      <h3
         class="text-zinc-500 uppercase text-center sticky 2xl:text-base xl:text-sm lg:text-xs text-[10px] top-0"
      >
         {{ props.title }}
      </h3>
      <main v-for="(group, key) of data">
         <button
            @click="timeLine = key"
            class="text-gray-500 xl:text-xs text-[10px] flex lg:flex-row justify-between lg:items-center items-start capitalize w-full hover:opacity-70 active:opacity-90"
            :key="key"
         >
            <span :class="`text-${color}-500 active:bg-${color}-500`">
               {{ replace(key) }}
            </span>
            <div
               v-if="color != 'gray'"
               :class="`text-${color}-500`"
               class="flex items-center"
            >
               {{ minuteFormat(group.summTime) }}
               <span
                  :class="`bg-${color}-400 bg-${color}-500`"
                  class="w-4 h-4 lg:ml-1 ml-0.5 text-base justify-center inline-flex items-center text-zinc-950 rounded-sm"
               >
                  <span class="lg:text-xs text-[10px] font-semibold">
                     {{ group.counter }}
                  </span>
               </span>
            </div>
         </button>
         <TransitionGroup
            tag="main"
            name="fade"
            :class="props.gridCols"
            class="grid gap-1.5 py-1.5"
         >
            <TransportButton
               v-for="(state, index) in group.cars"
               @click="$emit('openModal', state)"
               :state="state"
               :color="props.color"
               :key="index"
            />
            <TransportButton
               v-if="group.cars.length == 0"
               :state="null"
               :color="props.color"
            />
         </TransitionGroup>
      </main>
   </section>
</template>

<script setup lang="ts">
import TransportButton from "@/ui/TransportButton.vue";
import { minuteFormat } from "@/helpers/timeFormat";
import TransportProcessGroupModal from "./TransportProcessGroupModal.vue";
import { ref } from "vue";
const props = defineProps([
   "color",
   "title",
   "count",
   "data",
   "gridCols",
   "scrollColor",
]);

const timeLine = ref(null);

function replace(str) {
   return str
      .replace("Пересменка", "")
      .replace("Заправочный", "")
      .replace("РВ", "");
}
</script>
