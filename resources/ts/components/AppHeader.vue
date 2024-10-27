<template>
   <main class="bg-zinc-800 py-1 px-5 shadow text-white flex items-center justify-between">
      <div class="w-96"></div>
      <span class="text-xl mr-32 uppercase font-semibold tracking-widest text-stone-200">
         {{ $t('appname') }}
      </span>
      <div class="flex items-center">
         <section class="flex items-center mx-3">
            <span class="mx-4">
               {{ $t('minute') }}
            </span>
            <aside class="w-2 h-9 relative flex justify-center items-center text-xs">
               <span
                  class="w-6 h-6 bg-gray-400 rounded-full flex-shrink-0 text-gray-900 flex justify-center items-center font-bold">
                  1
               </span>
            </aside>
         </section>
         <section class="flex items-center mx-3">
            <span class="mx-4">
               {{ $t('hour') }}
            </span>
            <aside class="w-2 h-9 bg-yellow-400 relative flex justify-center items-center text-xs">
               <span
                  class="w-6 h-6 bg-gray-400 rounded-full flex-shrink-0 text-gray-900 flex justify-center items-center font-bold">
                  1
               </span>
            </aside>
         </section>
         <section class="flex items-center mx-3">
            <span class="mx-4">
               {{ $t('day') }}
            </span>
            <aside class="w-2 h-9 bg-gray-300 relative flex justify-center items-center text-xs">
               <span
                  class="w-6 h-6 bg-gray-400 rounded-full flex-shrink-0 text-gray-900 flex justify-center items-center font-bold">
                  1
               </span>
            </aside>
            <div v-if="auth.user == null">
               <router-link v-if="$route.name == 'home'" to="/login" class="px-5 ml-10">{{ $t('signin') }}</router-link>
               <router-link v-else to="/" class="px-5 ml-10">{{ $t('back') }}</router-link>
            </div>
            <div v-else>
               <button @click="auth.logout" class="px-5 ml-10">
                  {{ $t('exit') }}
               </button>
            </div>
         </section>
      </div>
   </main>
</template>

<script setup lang="ts">
import { AuthStore } from '@/app/auth'
const auth = AuthStore()
</script>