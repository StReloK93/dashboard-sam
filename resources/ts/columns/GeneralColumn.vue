<template>
   <main class="border-r border-zinc-800 flex flex-col">
      <div class="h-28 px-2 flex items-center justify-around">
         <aside>
            <Swiper :loop="true" effect="cards" class="w-[90px] h-[92px]" :slides-per-view="1" :slides-per-group="1"
               :modules="[EffectCards]">
               <slot name="firstSlider"></slot>
            </Swiper>
         </aside>
         <aside v-if="props.duo">
            <Swiper :loop="true" effect="cards" class="w-[90px] h-[92px]" :slides-per-view="1" :slides-per-group="1"
               :modules="[EffectCards]">
               <slot name="secondSlider"></slot>
            </Swiper>
         </aside>
      </div>
      <aside :class="props.scrollColor" class="overflow-y-auto flex-grow scroll overflow-x-hidden">
         <slot name="firstProcess"></slot>
         <slot v-if="props.duo" name="secondProcess"></slot>
      </aside>
   </main>
</template>
<script setup lang="ts">
import { Swiper } from 'swiper/vue'
import { EffectCards, Autoplay } from 'swiper/modules'

const props = defineProps(['duo', 'scrollColor'])
// const autoplay = { delay: 10000, disableOnInteraction: false }
</script>