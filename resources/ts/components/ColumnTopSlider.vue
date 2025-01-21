<template>
   <Swiper :loop="true" effect="cards" class="2xl:w-[90px] 2xl:h-[90px] xl:w-[78px] xl:h-[78px] w-[64px] h-[64px]" :slides-per-view="1" :slides-per-group="1"
      :modules="[EffectCards]">
      <SwiperSlide v-for="slide in props.slides" >
         <CircleUI @start="slide.onStart" @click="slide.onClick" :timer="slide.timer" :type="slide.type" :bgColor="slide.bgColor"
            :textColor="slide.textColor" :summa="slide.value" :class="slide.class">
            <i v-if="slide.icon" :class="slide.icon"></i>
            <component v-if="slide.component" :is="slide.component" v-bind="slide.componentParams" class="-scale-x-100 translate-y-1.5"></component>
         </CircleUI>
      </SwiperSlide>
   </Swiper>
</template>

<script setup lang="ts">
import { EffectCards, Autoplay } from 'swiper/modules'
import { SwiperSlide, Swiper } from 'swiper/vue'
import CircleUI from '@/ui/CircleUI.vue'
const props = defineProps(['slides'])
</script>