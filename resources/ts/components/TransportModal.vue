<template>
    <section @mouseup="store.close" class="fixed inset-0 bg-zinc-950/70 flex justify-center items-center">
        <aside @mouseup.stop class="w-[992px] h-[540px] relative">
            <main :class="{'!-top-28 !opacity-100': mounted }" class="absolute top-20 opacity-0 left-1/2 -translate-x-1/2 transition-all duration-200">
                <CircleUI :bgColor="carColor.stroke" :textColor="carColor.text" :summa="store.transport.name">
                    <i class="fa-duotone fa-truck-front"></i>
                </CircleUI>
            </main>
            <Swiper @init="initial" @slide-change="slideChange" :initial-slide="store.mode" :effect="'cards'" class="h-full"
                :slides-per-view="1" :modules="[EffectCards]">
                <SwiperSlide class="slider-item neomorph border-t-2 border-t-green-600">
                    <TransfromModalItem type="inExcavator" headerColor="bg-green-800" class="scroll green-scroll" />
                </SwiperSlide>
                <SwiperSlide class="slider-item neomorph border-t-2 border-t-yellow-600">
                    <TransfromModalItem type="inExcavator" header-color="bg-yellow-600" class="scroll yellow-scroll" />
                </SwiperSlide>
                <SwiperSlide class="slider-item neomorph border-t-2 border-t-orange-600">
                    <TransfromModalItem type="inOIL" header-color="bg-orange-600" class="scroll orange-scroll" />
                </SwiperSlide>
                <SwiperSlide class="slider-item neomorph border-t-2 border-t-indigo-600">
                    <TransfromModalItem type="inSmenaAll" header-color="bg-indigo-600" class="scroll indigo-scroll" />
                </SwiperSlide>
                <SwiperSlide class="slider-item neomorph border-t-2 border-t-red-600">
                    <TransfromModalItem type="transportsAll" header-color="bg-red-600" class="scroll red-scroll" />
                </SwiperSlide>
                <SwiperSlide class="slider-item neomorph border-t-2 border-t-gray-600">
                    <TransfromModalItem type="inATB" header-color="bg-gray-600" class="scroll gray-scroll" />
                </SwiperSlide>
            </Swiper>
        </aside>
    </section>
</template>

<script setup lang="ts">
import TransfromModalItem from './TransfromModalItem.vue'
import { TransportStates, TransportModal } from '@/entities/transports'
import { Swiper, SwiperSlide } from 'swiper/vue'
import { EffectCards } from 'swiper/modules'
import CircleUI from '@/ui/CircleUI.vue'
import { ref, computed, onMounted } from 'vue'
const store = TransportModal()
const activeSlide = ref(0)
const mounted = ref(false)

function slideChange(swiper) {
    activeSlide.value = swiper.activeIndex
}

function initial(swiper) {
    activeSlide.value = swiper.activeIndex
}

const carColor = computed(() => {
    if (activeSlide.value == 0) return { stroke: 'stroke-green-500', text: 'text-green-500' }
    if (activeSlide.value == 1) return { stroke: 'stroke-yellow-500', text: 'text-yellow-500' }
    if (activeSlide.value == 2) return { stroke: 'stroke-orange-500', text: 'text-orange-500' }
    if (activeSlide.value == 3) return { stroke: 'stroke-indigo-500', text: 'text-indigo-500' }
    if (activeSlide.value == 4) return { stroke: 'stroke-red-500', text: 'text-red-500' }
    if (activeSlide.value == 5) return { stroke: 'stroke-gray-500', text: 'text-gray-500' }
})

const transport = TransportStates()
transport.getTransportState(store.transport.transport_id)

onMounted(() => {
    setTimeout(() => mounted.value = true , 200);
})
</script>