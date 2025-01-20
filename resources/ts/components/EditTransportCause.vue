<template>
   <form @submit.prevent="saveCause(props.transport)" class="w-full flex items-center pr-2">
      <main class="truncate flex-grow">
         <div v-for="item in selected" :title="`${item?.name} ${item?.main}`">
            {{ item?.name }} {{ item?.main }}
         </div>
      </main>
      <Transition name="fade">
         <main v-if="menu" @vue:mounted="openModal"
            class="absolute top-1 right-2 backdrop-blur-sm bg-zinc-800/80 shadow-xl border border-zinc-900 w-[800px] h-96 z-50 flex flex-col">
            <div class="flex justify-between items-center pl-2 bg-indigo-600 text-white">
               <span>
                  {{ props.transport.name }}
               </span>
               <button type="button" @click="menu = false" class="w-8 h-8">
                  <i class="fa fa-close"> </i>
               </button>
            </div>
            <main class="flex h-full">
               <div class="w-1/2 h-full relative">
                  <div class="flex flex-wrap gap-2 absolute inset-0 p-2 overflow-x-hidden overflow-y-auto scroll indigo-scroll items-start content-start">
                     <div v-for="option in selectedOptions"
                        class="bg-indigo-600 pl-2 py-1 text-sm rounded inline-flex items-center">
                        <span>
                           {{ option.name }} {{ option.main }}
                        </span>
                        <button type="button" @click="select(option)" class="px-2 hover:text-red-600 relative top-px">
                           <i class="fa fa-close"> </i>
                        </button>
                     </div>
                  </div>
               </div>
               <div class="w-1/2 h-full flex flex-col">
                  <BaseSelect :options="props.causes" v-model="selectModel" class="flex-grow" />
                  <main class="text-right p-1">
                     <button type="submit" :disabled="buttonLoader"
                        class="bg-indigo-600 text-white w-20 h-8 font-semibold rounded shadow  relative">
                        <span v-if="buttonLoader == false">
                           Saqlash
                        </span>
                        <MiniPreLoader v-else></MiniPreLoader>
                     </button>
                  </main>
               </div>
            </main>
         </main>
      </Transition>

      <template
         v-if="(auth.user?.level == 1 && ['inSmenaAll', 'indigo'].includes(props.type)) || (auth.user?.level == 0 && ['inATB', 'grey'].includes(props.type))">
         <button @click="menu = true" type="button"
            class="min-w-10 h-10 py-0.5 text-indigo-500 hover:text-indigo-700 active:text-indigo-400">
            <i class="fa-solid fa-pen-nib "></i>
         </button>
      </template>
   </form>
</template>

<script setup lang="ts">
import BaseSelect from '@/components/BaseSelect.vue'
import { ref, computed } from 'vue'
import { AuthStore } from '@/app/auth'
const props = defineProps(['transport', 'causes', 'type'])
const auth = AuthStore()

const buttonLoader = ref(false)
const selectModel = ref([])


function select(option: any) {
   if (selectModel.value.includes(option.id)) selectModel.value = selectModel.value.filter((v) => v != option.id)
   else selectModel.value.push(option.id)
}

const selectedOptions = computed(() => {
   return props.causes.filter((option) => selectModel.value.includes(option.id))
})


const selected = computed(() => {
   return props.causes.filter((option) => {
      const ids = props.transport.causes.map((cause) => cause.cause_id)
      return ids.includes(option.id)
   })
})

const menu = ref(false)


function openModal() {
   selectModel.value = props.transport.causes.map((cause) => cause.cause_id)
}
async function saveCause(transport) {
   buttonLoader.value = true
   await axios.patch(`api/transportstates/${props.transport.id}`, { ...transport, causes: selectModel.value })
      .then(({ data }) => props.transport.causes = data)
      .catch(() => buttonLoader.value = false)
   menu.value = false
   buttonLoader.value = false
}


</script>