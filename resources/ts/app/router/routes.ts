export default [
   {
      path: '/',
      component: () => import('@/pages/Home.vue')
   },
   {
      path: '/mapping',
      component: () => import('@/pages/Map.vue')
   },
]