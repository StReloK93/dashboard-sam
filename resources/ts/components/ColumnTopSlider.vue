<template>
   <Swiper :loop="true" effect="cards" class="w-[90px] h-[92px]" :slides-per-view="1" :slides-per-group="1"
      :modules="[EffectCards]">
      <SwiperSlide v-for="slide in props.slides" class="!w-[90px]">
         <CircleUI
            @start="slide.start()"
            @click="slide.click()"
            :timer="slide.timer"
            :bgColor="slide.bgColor"
            :textColor="slide.textColor"
            :summa="process"
            :class="slide.class"
         >
            <i v-if="slide.icon" :class="slide.icon"></i>
            <component v-if="slide.component" :is="slide.component" v-bind="slide.componentParams"></component>
         </CircleUI>
      </SwiperSlide>
   </Swiper>
</template>

<script setup lang="ts">
const process = defineModel('process')
import { EffectCards, Autoplay } from 'swiper/modules'
import { SwiperSlide, Swiper } from 'swiper/vue'
import CircleUI from '@/ui/CircleUI.vue'
const props = defineProps(['slides'])


</script>