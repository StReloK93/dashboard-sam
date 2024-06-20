<template>
   <form @submit.prevent="saveCause(props.transport)" class="w-full flex items-center pr-2">
      <div class="flex-grow truncate" :title="`${ selected?.name } ${ selected?.main }`">
         {{ selected?.name }} {{ selected?.main }}
      </div>

      <Transition name="fade">
         <main v-if="menu" @vue:mounted="openModal" class="absolute top-1 right-2 backdrop-blur-sm bg-zinc-800/80 shadow-xl border border-zinc-900 w-[450px] h-96 z-50 flex flex-col">
            <div class="flex justify-between items-center pl-2 bg-indigo-600 text-white">
               <span>
                  {{ props.transport.name }}
               </span>
               <button type="button" @click="menu = false" class="w-8 h-8">
                  <i class="fa fa-close"> </i>
               </button>
            </div>
            <BaseSelect :options="causes" v-model="selectModel" class="flex-grow"/>
            <main class="text-right p-1">
               <button type="submit" :disabled="buttonLoader" class="bg-indigo-600 text-white w-20 h-8 font-semibold rounded shadow  relative">
                  <span v-if="buttonLoader == false">
                     Saqlash
                  </span>
                  <MiniPreLoader v-else></MiniPreLoader>
               </button>
            </main>
         </main>
      </Transition>

      <template v-if="(auth.user?.level == 1)">
         <button @click="menu = true" type="button" class="w-10 h-10 py-0.5 text-indigo-500 hover:text-indigo-700 active:text-indigo-400">
            <i class="fa-solid fa-pen-nib "></i>
         </button>
      </template>
   </form>
</template>

<script setup lang="ts">
import BaseSelect from '@/components/BaseSelect.vue'
import { ref, computed } from 'vue'
import { AuthStore } from '@/app/auth'
const props = defineProps(['transport', 'causes'])
const auth = AuthStore()

const buttonLoader = ref(false)
const selectModel = ref(null)


const selected = computed(() => {
   if (isNaN(+props.transport.cause)) {
      return {name: props.transport.cause}
   }
   else {
      return props.causes.find((option) => option.id == props.transport.cause)
   }
})

const menu = ref(false)


function openModal() {
   selectModel.value = props.transport.cause
}
async function saveCause(transport) {
   buttonLoader.value = true
   await axios.patch(`api/transportstates/${props.transport.id}`, { ...transport, cause: selectModel.value }).then(({ data }) => {
      props.transport.cause = selectModel.value
   })
   menu.value = false
   buttonLoader.value = false
}


</script>