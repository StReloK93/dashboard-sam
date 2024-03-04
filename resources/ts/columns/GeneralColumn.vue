<template>
   <main class="border-r border-zinc-800 flex flex-col">
      <ColumnHeader>
         <main>
            <Swiper effect="cards" class="w-[90px] h-[92px]" :slides-per-view="1" :modules="[EffectCards]">
               <slot name="firstSlider"></slot>
            </Swiper>
         </main>
         <main v-if="props.duo">
            <Swiper :autoplay="autoplay" effect="cards" class="w-[90px] h-[92px]" :slides-per-view="1"
               :modules="[EffectCards, Autoplay]">
               <slot name="secondSlider"></slot>
            </Swiper>
         </main>
      </ColumnHeader>
      <aside :class="props.scrollColor" class="overflow-y-auto flex-grow scroll overflow-x-hidden">
         <slot name="firstProcess"></slot>
         <slot v-if="props.duo" name="secondProcess"></slot>
      </aside>
   </main>
</template>
<script setup lang="ts">
import { Swiper } from 'swiper/vue'
import ColumnHeader from '@/ui/ColumnHeader.vue'
import { EffectCards, Autoplay } from 'swiper/modules'

const props = defineProps(['duo', 'scrollColor'])
const autoplay = { delay: 10000, disableOnInteraction: false }
</script>