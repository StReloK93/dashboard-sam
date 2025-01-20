<template>
   <section class="flex flex-col">
      <main class="flex px-1.5 border-b border-gray-700">
         <input type="text" v-model="filterText" class="bg-transparent outline-none h-10 w-full" placeholder="Qidirish">
      </main>
      <main class="flex-grow relative w-full text-sm">
         <aside class="absolute inset-0 overflow-x-hidden overflow-y-auto scroll indigo-scroll shadow">
            <template v-for="option in options">
               <div @click="select(option)" :class="{ 'bg-zinc-900': model.includes(option.id) }"
                  class="py-2 px-1.5 hover:bg-zinc-900 cursor-pointer">
                  {{ option.name }} {{ option.main }}
               </div>
            </template>
         </aside>
      </main>
   </section>
</template>

<script setup lang="ts">
import { computed, ref, Ref } from 'vue'
const props = defineProps(['options'])
const model: Ref<any[]> = defineModel()
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

const selectedOptions = computed(() => {
   return props.options.filter((option) => model.value.includes(option.id))
})


function select(option: any) {
   if (model.value.includes(option.id)) model.value = model.value.filter((v) => v != option.id)
   else model.value.push(option.id)
}
</script>