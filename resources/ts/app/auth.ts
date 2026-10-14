import { defineStore } from "pinia";
import { ref, computed } from "vue";
import router from "@/app/router";
import { wialonHttp } from "../helpers/useFetchWialon";

export const AuthStore = defineStore("Auth", () => {
   const user: any = ref(null);

   async function login(data: any) {
      try {
         const result = await wialonHttp.post("login", data);
         if (result.status == 299) return result.data;
         else {
            localStorage.setItem(
               "token",
               `${result.data.type} ${result.data.token}`,
            ); // local
            await getUser();
            router.push({ name: "home" });
         }
      } catch (error: any) {
         return error.response.data;
      }
   }

   const userRoles = computed(() => {
      if (user.value)
         return user.value?.roles?.map((role: any) => role.role_id);
      else return [];
   });

   async function getUser() {
      wialonHttp.defaults.headers.common["Authorization"] =
         localStorage.getItem("token");
      await wialonHttp
         .get("user")
         .then(({ data }) => {
            user.value = data;
         })
         .catch(() => {
            console.clear();
         });
   }

   async function register(props: any) {
      try {
         const result = await wialonHttp.post("register", props);
         if (result.status == 200) login(props);
         else return result.data;
      } catch (err: any) {
         return err.response.data;
      }
   }

   async function logout() {
      const data = await wialonHttp.get("logout");

      if (data.status == 200) {
         wialonHttp.defaults.headers.common["Authorization"] = null;
         localStorage.removeItem("token");
         user.value = null;
         router.push({ name: "login" });
      }
   }

   return { user, userRoles, getUser, login, register, logout };
});
