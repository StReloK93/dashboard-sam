<template>
   <Swiper :loop="true" effect="cards" class="w-[90px] h-[92px]" :slides-per-view="1" :slides-per-group="1"
      :modules="[EffectCards]">
      <SwiperSlide v-for="slide in slides" class="!w-[90px]">
         <CircleUI @start="slide.start" @click="slide.click" :timer="slide.timer" :bgColor="slide.bgColor"
            :textColor="slide.textColor" :summa="slide.value" :class="slide.class">
            <i v-if="slide.icon" :class="slide.icon"></i>
            <component v-if="slide.component" :is="slide.component" v-bind="slide.componentParams"></component>
         </CircleUI>
      </SwiperSlide>
   </Swiper>
</template>

<script setup lang="ts">
import { EffectCards, Autoplay } from 'swiper/modules'
import { SwiperSlide, Swiper } from 'swiper/vue'
import type { ISlides } from '@/app/interface'
import { Ref } from 'vue'
import CircleUI from '@/ui/CircleUI.vue'
const slides:Ref<ISlides[]>  = defineModel('slides')

</script>