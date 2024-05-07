export default [
   {
      path: '/',
      component: () => import('@/pages/Home.vue'),
      name: 'home'
   },
   {
      path: '/login',
      component: () => import('@/pages/Login.vue'),
      meta: {
         guard: 'guest',
      },
      name: 'login'

   },
]