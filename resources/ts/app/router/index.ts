import { createRouter, createWebHistory } from "vue-router"
import routes from '@/app/router/routes'
const router = createRouter({
   history: createWebHistory(),
   routes, // short for `routes: routes`
})

export default router