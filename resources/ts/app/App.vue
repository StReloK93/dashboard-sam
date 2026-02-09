<template>
   <section
      :class="[
         setting.excavators
            ? 'xl:grid-rows-[36px,30px,1fr,auto] lg:grid-rows-[31px,30px,1fr,135px,68px] grid-rows-[31px,30px,1fr,135px,68px]'
            : 'xl:grid-rows-[38px,35px,1fr] grid-rows-[31px,30px,1fr]',
      ]"
      class="bg-zinc-900 h-screen grid overflow-hidden overflow-y-auto scroll indigo-scroll"
   >
      <AppNavigator />
      <header
         class="bg-zinc-800 py-1 px-1 shadow text-white flex items-center justify-between"
      >
         <div class="xl:w-96"></div>
         <span
            class="2xl:text-base xl:text-base text-xs uppercase font-semibold tracking-widest text-stone-200"
         >
            {{ $t("appname") }}
         </span>
         <div class="flex items-center">
            <section class="flex items-center xl:mx-3 mx-2">
               <span class="xl:mx-4 mx-3 2xl:text-base xl:text-sm text-[13px]">
                  {{ $t("minute") }}
               </span>
               <aside
                  class="w-2 relative flex justify-center items-center text-xs"
               >
                  <span
                     class="xl:w-6 xl:h-6 w-[22px] h-[22px] bg-gray-400 rounded-full flex-shrink-0 text-gray-900 flex justify-center items-center font-bold"
                  >
                     1
                  </span>
               </aside>
            </section>
            <section class="flex items-center xl:mx-3 mx-2">
               <span class="xl:mx-4 mx-3 2xl:text-base xl:text-sm text-[13px]">
                  {{ $t("hour") }}
               </span>
               <aside
                  class="w-2 h-8 bg-yellow-400 relative flex justify-center items-center text-xs"
               >
                  <span
                     class="xl:w-6 xl:h-6 w-[22px] h-[22px] bg-gray-400 rounded-full flex-shrink-0 text-gray-900 flex justify-center items-center font-bold"
                  >
                     1
                  </span>
               </aside>
            </section>
            <section class="flex items-center xl:mx-3 mx-2">
               <span class="xl:mx-4 mx-3 2xl:text-base xl:text-sm text-[13px]">
                  {{ $t("day") }}
               </span>
               <aside
                  class="w-2 h-8 bg-gray-300 relative flex justify-center items-center text-xs"
               >
                  <span
                     class="xl:w-6 xl:h-6 w-[22px] h-[22px] bg-gray-400 rounded-full flex-shrink-0 text-gray-900 flex justify-center items-center font-bold"
                  >
                     1
                  </span>
               </aside>
               <div v-if="auth.user == null">
                  <router-link
                     v-if="$route.name == 'home'"
                     to="/login"
                     class="px-5 ml-10"
                     >{{ $t("signin") }}</router-link
                  >
                  <router-link v-else to="/" class="px-5 ml-10">{{
                     $t("back")
                  }}</router-link>
               </div>
               <div v-else>
                  <button
                     @click="auth.logout"
                     class="px-5 xl:ml-10 ml-4 2xl:text-base xl:text-sm text-[14px]"
                  >
                     {{ $t("exit") }}
                  </button>
               </div>
            </section>
         </div>
      </header>

      <main class="relative">
         <router-view class="w-full absolute inset-0"></router-view>
      </main>

      <MobileChartColumn class="xl:hidden" />
      <footer
         v-if="setting.excavators"
         class="bg-zinc-900 xl:px-3 p-1.5 shadow flex flex-wrap content-center xl:gap-1 gap-0.5 justify-between neomorph relative"
      >
         <div
            v-for="car in store.ExcavatorList"
            :class="[
               car.status_of == 'Ishda'
                  ? '!border-teal-400 neomorph bg-zinc-800'
                  : '!border-zinc-900 bg-zinc-950 shadow-inner',
            ]"
            class="text-xs xl:w-10 xl:h-10 w-7 h-7 rounded-full text-center inline-flex items-center justify-center flex-col border"
         >
            <main class="text-teal-400 xl:text-base font-semibold">
               {{ car.number }}
            </main>
            <tippy
               v-if="
                  (car.cause_old && car.status_of != 'Ishda') ||
                  (car.cause && car.status_of != 'Ishda')
               "
               target="_parent"
            >
               <div class="font-semibold" v-if="car.cause_old">
                  {{ car.cause_old }}
               </div>
               <div class="font-semibold" v-if="car.cause">{{ car.cause }}</div>
            </tippy>
         </div>
      </footer>
   </section>
</template>

<script setup lang="ts">
import { AuthStore } from "@/app/auth";
import { Excavators } from "@/entities/transports/Excavators";
import AppNavigator from "@/components/AppNavigator.vue";
import MobileChartColumn from "@/columns/MobileChartColumn.vue";
const auth = AuthStore();
const store = Excavators();
const setting = settings;
</script>
