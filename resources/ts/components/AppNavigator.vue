<template>
   <footer class="bg-zinc-900 px-1 flex justify-between items-center text-white">
      <div class="relative xl:top-0 -top-px">
         <a v-for="(dashboard, index) in links" :href="`http://${dashboard.url}`"
            :class="[{ 'neomorph !bg-orange-600': hostname == dashboard.url }]"
            class="inline-block content-center bg-zinc-800 mr-1 2xl:w-40 xl:w-28 lg:w-24 w-20 py-0.5 2xl:text-base xl:text-sm text-xs rounded text-center">
            {{ $t(dashboard.name) }}
         </a>
      </div>
      <div class="flex">
         <a :href="urlExcavator" class="px-2 py-0.5 text-teal-400 uppercase inline-flex items-center mr-4 xl:text-sm text-xs">
            <i class="fa-solid fa-house mr-2"></i>
            {{t('mainpage')}}
         </a>
         <button :class="{ 'bg-zinc-800 grayscale-0': $i18n.locale == 'uz' }"
            class="px-2 py-0.5 grayscale transition-all uppercase inline-flex items-center mr-4 xl:text-base text-xs"
            @click="$i18n.locale = 'uz'">
            <img src="/images/uz.png" class="xl:w-7 w-6"> uz
         </button>
         <button :class="{ 'bg-zinc-800 grayscale-0': $i18n.locale == 'ru' }"
            class="px-2  py-0.5 grayscale transition-all uppercase inline-flex items-center xl:text-base text-xs" @click="$i18n.locale = 'ru'">
            <img src="/images/ru.png" class="xl:w-7 w-6"> ru
         </button>
      </div>
   </footer>
</template>

<script setup lang="ts">
import { computed, ref, watch } from 'vue'
import { useI18n } from 'vue-i18n'


const hostname = ref(window.location.host)

const urlExcavator = settings.excavator_page
const { locale, t } = useI18n({ useScope: 'global' })

const dashboardLinks = ref([
   { url: '192.168.14.23:3017', name: 'sharqiy' },
   { url: '192.168.14.23:3009', name: 'daugiztau' },
   { url: '192.168.14.23:3016', name: 'amantay' },
   { url: '192.168.14.23:3018', name: 'turbay',  },
   { url: '192.168.48.7:3001', name: 'muruntau' },
   { url: '172.17.12.12:8000', name: 'pistali' },
])

const links = computed(() => {
   if(settings.only_myip.includes(settings.user_ip)){
      return dashboardLinks.value.filter((link) => link.url.indexOf("192.168.14.23") >= 0)
   }
   else{
      return dashboardLinks.value
   }
})


watch(() => locale.value, (current) => {
   localStorage.setItem('locale', current)
})
</script>