<template>
   <section class="flex flex-col">
      <main class="flex px-1.5 border-b border-gray-700">
         <main :class="{'w-3/4 pr-8 px-2': selected }" class="flex pl-0 relative">
            <button v-if="selected" type="button" @click="clearSelected" class="absolute top-2 right-0 w-8 h-6">
               <i class="fa fa-close"> </i>
            </button>
            <template v-if="selected">
               <p class="truncate content-center block" :title="`${ selected?.name } ${ selected?.main }`">
                  {{ selected?.name }} {{ selected?.main }}
               </p>
            </template>
         </main>
         <input type="text" :class="{'w-1/4': selected }" v-model="filterText" class="bg-transparent outline-none h-10 w-full" placeholder="Qidirish">
      </main>
      <main class="flex-grow relative w-full text-sm">
         <aside class="absolute inset-0 overflow-x-hidden overflow-y-auto scroll indigo-scroll shadow">
            <div @click="select(option)" v-for="option in options" :class="{'bg-zinc-900': option.id == model}" class="py-2 px-1.5 hover:bg-zinc-900">
               {{ option.name }} {{ option.main }}
            </div>
         </aside>
      </main>
   </section>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
const props = defineProps(['options'])
const model = defineModel()
const filterText = ref(null)

const options = computed(() => {
   return props.options.filter((option) => {
      if (['', null].includes(filterText.value)) {
         return true
      }
      else {
         const fullText = `${option.name} ${option.main}`.toLowerCase()
         return fullText.includes(filterText.value.toLowerCase())
      }
   })
})

function select(option) {
   model.value = option.id
}


async function clearSelected() {
   model.value = null
}


const selected = computed(() => props.options.find((option) => option.id == model.value))

</script>