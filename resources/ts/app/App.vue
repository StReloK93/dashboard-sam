<template>
    <section class="bg-zinc-900 h-screen grid grid-rows-[30px,1fr] overflow-hidden overflow-y-auto scroll indigo-scroll">
        <main class="bg-zinc-800 py-1 px-2 shadow text-white flex items-center justify-between">
            <span>
                Test
            </span>


            <div>
                
            </div>
        </main>
        <main class="relative">
            <router-view class="w-full absolute inset-0"></router-view>
        </main>
    </section>
</template>

<script setup lang="ts">
</script>
