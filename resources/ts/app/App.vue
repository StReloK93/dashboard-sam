<template>
    <section :class="[setting.excavators ? 'grid-rows-[38px,35px,1fr,80px]' : 'grid-rows-[38px,35px,1fr]']"
        class="bg-zinc-900 h-screen grid  overflow-hidden overflow-y-auto scroll indigo-scroll">
        <AppNavigator />
        <AppHeader />
        <main class="relative">
            <router-view class="w-full absolute inset-0"></router-view>
        </main>
        <AppFooter v-if="setting.excavators" />
    </section>
</template>

<script setup lang="ts">
import AppHeader from '@/components/AppHeader.vue'
import AppFooter from '@/components/AppFooter.vue';
import AppNavigator from '@/components/AppNavigator.vue'

const setting = settings
</script>
