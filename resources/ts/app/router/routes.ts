export default [
   {
      path: '/',
      component: () => import('@/pages/Home.vue')
   },
   {
      path: '/tables',
      component: () => import('@/pages/Tables.vue')
   },
   {
      path: '/mapping',
      component: () => import('@/pages/Map.vue')
   },
]