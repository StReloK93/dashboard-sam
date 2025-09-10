<template>
    <section :class="[setting.excavators ? 'xl:grid-rows-[36px,30px,1fr,52px] lg:grid-rows-[31px,30px,1fr,135px,68px] grid-rows-[31px,30px,1fr,135px,68px]' : 'xl:grid-rows-[38px,35px,1fr] grid-rows-[31px,30px,1fr]']"
        class="bg-zinc-900 h-screen grid  overflow-hidden overflow-y-auto scroll indigo-scroll">
        <AppNavigator />
        <AppHeader />
        <main class="relative">
            <router-view class="w-full absolute inset-0"></router-view>
        </main>
        <MobileChartColumn class="xl:hidden"  />
        <AppFooter v-if="setting.excavators" />
    </section>
</template>

<script setup lang="ts">
import AppHeader from '@/components/AppHeader.vue'
import AppFooter from '@/components/AppFooter.vue';
import AppNavigator from '@/components/AppNavigator.vue'
import MobileChartColumn from '@/columns/MobileChartColumn.vue';
const setting = settings
</script>
