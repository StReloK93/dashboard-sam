<template>
   <main class="bg-zinc-800 py-1 px-1 shadow text-white flex items-center justify-between">
      <div class="xl:w-96"></div>
      <span class="2xl:text-xl xl:text-base text-xs uppercase font-semibold tracking-widest text-stone-200">
         {{ $t('appname') }}
      </span>
      <div class="flex items-center">
         <section class="flex items-center xl:mx-3 mx-2">
            <span class="xl:mx-4 mx-3 2xl:text-base xl:text-sm text-[13px]">
               {{ $t('minute') }}
            </span>
            <aside class="w-2 relative flex justify-center items-center text-xs">
               <span
                  class="xl:w-6 xl:h-6 w-[22px] h-[22px] bg-gray-400 rounded-full flex-shrink-0 text-gray-900 flex justify-center items-center font-bold">
                  1
               </span>
            </aside>
         </section>
         <section class="flex items-center xl:mx-3 mx-2">
            <span class="xl:mx-4 mx-3 2xl:text-base xl:text-sm text-[13px]">
               {{ $t('hour') }}
            </span>
            <aside class="w-2 xl:h-9 h-[30px] bg-yellow-400 relative flex justify-center items-center text-xs">
               <span
                  class="xl:w-6 xl:h-6 w-[22px] h-[22px] bg-gray-400 rounded-full flex-shrink-0 text-gray-900 flex justify-center items-center font-bold">
                  1
               </span>
            </aside>
         </section>
         <section class="flex items-center xl:mx-3 mx-2">
            <span class="xl:mx-4 mx-3 2xl:text-base xl:text-sm text-[13px]">
               {{ $t('day') }}
            </span>
            <aside class="w-2 xl:h-9 h-[30px] bg-gray-300 relative flex justify-center items-center text-xs">
               <span
                  class="xl:w-6 xl:h-6 w-[22px] h-[22px] bg-gray-400 rounded-full flex-shrink-0 text-gray-900 flex justify-center items-center font-bold">
                  1
               </span>
            </aside>
            <div v-if="auth.user == null">
               <router-link v-if="$route.name == 'home'" to="/login" class="px-5 ml-10">{{ $t('signin') }}</router-link>
               <router-link v-else to="/" class="px-5 ml-10">{{ $t('back') }}</router-link>
            </div>
            <div v-else>
               <button @click="auth.logout" class="px-5 xl:ml-10 ml-4 2xl:text-base xl:text-sm text-[14px]">
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