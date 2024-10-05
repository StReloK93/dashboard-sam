<template>
   <footer class="bg-zinc-900 py-1 px-1.5">
      <a v-for="(dashboard, index) in dashboardLinks" :href="`http://${dashboard.url}`"
         :class="[{'neomorph !bg-orange-600': hostname == dashboard.url }]"      
         class="h-full inline-block content-center bg-zinc-800 mr-1 px-3 w-40 text-white rounded text-center"
         >
         {{ dashboard.name }}
      </a>
   </footer>
</template>

<script setup lang="ts">
import { ref } from 'vue'
const hostname = ref(window.location.host)


const dashboardLinks = ref([
   // { url: '127.0.0.1:8000', name: 'Localhost' },
   {url: '192.168.14.23:3017', name: 'Sharqiy koni' },
   {url: '192.168.14.23:3009', name: 'Daugiztau koni' },
   {url: '192.168.48.7:3000', name: 'Amantay koni' },
   {url: '192.168.48.7:3001', name: 'Muruntau koni' },
   {url: '172.17.6.15:8000', name: 'Pistali koni' },
])

</script>