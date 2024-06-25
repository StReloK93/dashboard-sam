<template>
   <main class="border-r border-zinc-800 flex flex-col">
      <Transition name="fade">
         <ReportModal @close="reportModal = false" v-if="reportModal" />
      </Transition>
      <div class="h-28 px-2 flex items-center justify-around relative">
         <ColumnTopSlider :slides="greySlides" />
         <a :href="setting.table_link" class="absolute top-1 right-1 border-2 border-gray-400 text-gray-300 font-semibold py-1 px-2 shadow shadow-gray-600 rounded text-sm">
            TXK <i class="fa-solid fa-table-rows"></i>
         </a>
      </div>
      <aside class="gray-scroll overflow-y-auto flex-grow scroll overflow-x-hidden">
         <TransportProcess counter="timer" grid-cols="grid-cols-3"
            @openModal="(transport) => store.openModal(5, transport)" title="ATB maydonida" color="gray"
            :data="transportStore.inATB" />
      </aside>
   </main>
</template>

<script setup lang="ts">
import { reactive, computed, ref } from 'vue'
import ColumnTopSlider from '@/components/ColumnTopSlider.vue'
import { Transports, TransportModal } from '@/entities/transports'
import TransportProcess from '@/components/TransportProcess.vue'
import ReportModal from '@/components/ReportModal.vue'
import { AuthStore } from '@/app/auth'
const store = TransportModal()
const transportStore = Transports()
const AUTH = AuthStore()
const reportModal = ref(false)
const setting = settings
const greySlides:any = reactive([
   {
      bgColor: 'stroke-gray-400',
      textColor: 'text-gray-400',
      timer: 30,
      value: computed(() => transportStore.inATB?.length),
      component: 'TruckIcon',
      componentParams: { width: 22, color: "fill-gray-400", colorSecond: "fill-gray-700" },
   },
   {
      onClick: () => reportModal.value = true,
      bgColor: 'stroke-gray-400',
      textColor: 'text-gray-400',
      class: "hover:bg-zinc-800 cursor-pointer active:bg-zinc-900",
      timer: 30,
      value: "Hisobot",
      icon: "fa-duotone fa-scroll"
   },
])

if (settings.table_link) {
   greySlides.push({
      onClick: () => { window.location = settings.table_link },
      bgColor: 'stroke-gray-400',
      textColor: 'text-gray-400',
      class: "hover:bg-zinc-800 cursor-pointer active:bg-zinc-900",
      timer: 30,
      value: "Jadval",
      icon: "fa-solid fa-table-rows"
   })
}

</script>