import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/LoginView.vue'),
    },
    {
      path: '/register',
      name: 'register',
      component: () => import('../views/RegisterView.vue'),
    },
    {
      path: '/informations',
      name: 'informations',
      component: () => import('../views/InformationsView.vue'),
    },
    {
      path: '/info/:id',
      name: 'info-detail',
      component: () => import('../views/InfoDetailView.vue'),
    },
    {
      path: '/relax',
      name: 'relax',
      component: () => import('../views/ActivityRelaxView.vue'),
    },
    {
      path: '/relax/:id',
      name: 'relax-detail',
      component: () => import('../views/RelaxDetailView.vue'),
    }
    
  ],
})

export default router
