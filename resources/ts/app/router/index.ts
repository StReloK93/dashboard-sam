import { createRouter, createWebHistory } from "vue-router"
import routes from '@/app/router/routes'
import { AuthStore } from '@/app/auth'

const router = createRouter({
   history: createWebHistory(),
   routes,
})


router.beforeEach((to, from, next) => {
	const store = AuthStore()
	if (store.user) {
		if (to.meta.guard === 'guest') next({ name: 'home' })
		else next()

	} else {
		if (to.meta.guard === 'auth') next({ name: 'login' })
		else next()
	}
})


export default router