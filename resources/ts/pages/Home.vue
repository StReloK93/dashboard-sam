<template>
   <section class="flex h-full">
      <GreenYellowColumn />
      <OrangeColumn />
      <IndigoColumn />
      <RedColumn />
      <GreyColumn />
      <ChartColumn class="xl:block hidden" />

      <TransitionGroup name="fade">
         <SingleTransport class="z-50" v-if="store.transport" />
      </TransitionGroup>
   </section>
</template>

<script setup lang="ts">
import GreenYellowColumn from "@/columns/GreenYellowColumn.vue";
import OrangeColumn from "@/columns/OrangeColumn.vue";
import IndigoColumn from "@/columns/IndigoColumn.vue";
import RedColumn from "@/columns/RedColumn.vue";
import GreyColumn from "@/columns/GreyColumn.vue";

import ChartColumn from "@/columns/ChartColumn.vue";
import SingleTransport from "@/components/TransportModal.vue";
import { TransportModal } from "@/entities/transports";
const store = TransportModal();
</script>
