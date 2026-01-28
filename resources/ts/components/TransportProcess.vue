<template>
   <section :class="scrollColor" class="xl:px-2 px-1">
      <h3
         class="text-zinc-500 uppercase text-center 2xl:text-base xl:text-sm lg:text-xs text-[10px]"
      >
         {{ props.title }}
      </h3>
      <TransitionGroup
         tag="main"
         name="fade"
         :class="props.gridCols"
         class="grid xl:gap-1.5 gap-1 py-1.5"
      >
         <TransportButton
            v-for="(state, index) in props.data"
            @click="$emit('openModal', state)"
            :key="index"
            :state="state"
            :color="props.color"
         />
         <TransportButton
            v-if="props.data?.length == 0"
            :color="props.color"
            :state="null"
         />
      </TransitionGroup>
   </section>
</template>

<script setup lang="ts">
import TransportButton from "@/ui/TransportButton.vue";
const props = defineProps([
   "color",
   "title",
   "count",
   "data",
   "gridCols",
   "counter",
   "scrollColor",
]);
</script>
