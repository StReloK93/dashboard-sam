<template>
   <section class="flex justify-center items-center">
      <div class="w-[525px] mx-auto text-center  rounded-lg relative overflow-hidden p-10">
         <form @submit.prevent="auth.login(formData)">
            <div class="mb-6">
               <input type="text" v-model="formData.login" placeholder="Login" class="w-full rounded border p-3 bg-[#FCFDFE] text-base text-body-color placeholder-[#ACB6BE] outline-none" />
            </div>
            <div class="mb-6">
               <input type="password" v-model="formData.password" placeholder="Parol" class="w-full rounded border p-3 bg-[#FCFDFE] text-base text-body-color placeholder-[#ACB6BE] outline-none" />
            </div>
            <div class="mb-10">
               <input type="submit" value="Sign In" class="w-full rounded border border-primary p-3 bg-primary text-base text-white cursor-pointer hover:bg-opacity-90 transition" />
            </div>
         </form>
      </div>
   </section>
</template>

<script setup lang="ts">
import { useAuthStore } from '@/app/auth'
import { reactive } from 'vue'
const formData = reactive({
   login: null,
   password: null,
})

const auth = useAuthStore()
</script>