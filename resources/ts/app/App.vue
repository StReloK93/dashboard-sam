<template>
    <section class="bg-zinc-900 h-screen grid grid-rows-[35px,1fr] overflow-hidden overflow-y-auto scroll indigo-scroll">
        <AppHeader />
        <main class="relative">
            <router-view class="w-full absolute inset-0"></router-view>
        </main>
    </section>
</template>

<script setup lang="ts">
import AppHeader from '@/components/AppHeader.vue'
</script>
