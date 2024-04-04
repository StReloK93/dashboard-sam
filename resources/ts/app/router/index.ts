import { createRouter, createWebHistory } from "vue-router"
import routes from '@/app/router/routes'
import { useAuthStore } from '@/app/auth'

const router = createRouter({
   history: createWebHistory(),
   routes,
})


router.beforeEach((to, from, next) => {
	const store = useAuthStore()
	if (store.user) {
		if (to.meta.guard === 'guest') next({ name: 'home' })
		else next()

	} else {
		if (to.meta.guard === 'auth') next({ name: 'login' })
		else next()
	}
})


export default router