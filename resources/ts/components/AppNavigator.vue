<template>
   <footer class="bg-zinc-900 py-1 px-1.5 flex justify-between items-center">
      <div>
         <a v-for="(dashboard, index) in dashboardLinks" :href="`http://${dashboard.url}`"
            :class="[{ 'neomorph !bg-orange-600': hostname == dashboard.url }]"
            class="h-full inline-block content-center bg-zinc-800 mr-1 px-3 w-40 text-white rounded text-center">
            {{ $t(dashboard.name) }}
         </a>
      </div>
      <div class="flex">
         <button :class="{ 'bg-zinc-800 grayscale-0': $i18n.locale == 'uz' }"
            class="px-2 py-0.5 grayscale transition-all text-white uppercase inline-flex items-center mr-4"
            @click="$i18n.locale = 'uz'">
            <img src="/images/uz.png" class="w-7"> uz
         </button>
         <button :class="{ 'bg-zinc-800 grayscale-0': $i18n.locale == 'ru' }"
            class="px-2  py-0.5 grayscale transition-all text-white uppercase inline-flex items-center" @click="$i18n.locale = 'ru'">
            <img src="/images/ru.png" class="w-7"> ru
         </button>
      </div>
   </footer>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { useI18n } from 'vue-i18n'
const hostname = ref(window.location.host)

const { locale } = useI18n({ useScope: 'global' })

const dashboardLinks = ref([
   { url: '192.168.14.23:3017', name: 'sharqiy' },
   { url: '192.168.14.23:3009', name: 'daugiztau' },
   { url: '192.168.14.23:3016', name: 'amantay' },
   { url: '192.168.48.7:3001', name: 'muruntau' },
   { url: '172.17.6.15:8000', name: 'pistali' },
])


watch(() => locale.value, (current) => {
   localStorage.setItem('locale', current)
})
</script>