<template>
   <button
      v-for="value in slides"
      @click="value.onClick"
      class="bg-zinc-950 inline-flex items-center justify-center gap-2 px-4 py-1 shadow rounded w-18 font-semibold text-sm h-8"
      :class="[value.textColor]"
   >
      <component
         v-if="value.component"
         :is="value.component"
         v-bind="value.componentParams"
         class="-scale-x-100"
      ></component>
      <i v-if="value.icon" :class="value.icon" class="text-xs"></i>
      {{ value.value }}
   </button>
</template>

<script setup lang="ts">
const props = defineProps(["slides"]);
</script>
