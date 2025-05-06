import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import { useUserStore } from '@/stores/userStore'

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
      path: '/reset-password',
      name: 'reset-password',
      component: () => import('../views/ResetPasswordView.vue'),
      props: (route) => ({ token: route.query.token })
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
    },
    {
      path: '/account',
      name: 'account',
      component: () => import('../views/AccountView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/favorites',
      name: 'favorites',
      component: () => import('../views/FavoritesView.vue'),
    },
    {
      path: '/history',
      name: 'history',
      component: () => import('../views/HistoryView.vue'),
    },
    {
      path: '/breathing',
      name: 'breathing',
      component: () => import('../views/BreathingView.vue'),
    },
    {
      path: '/admin',
      name: 'admin',
      component: () => import('@/views/AdminDashboard.vue'),
      meta: { requiresAuth: true, requiresAdmin: true }
    },
    {
      path: '/cgu',
      name: 'cgu',
      component: () => import('../views/CGUView.vue'),
    },
    
  ],
})

router.beforeEach(async (to, from, next) => {
  const userStore = useUserStore()

  // Si la route nécessite une auth et qu'on n'est pas connecté
  if (to.meta.requiresAuth && !userStore.token) {
    next('/login') // Redirige vers login
  } else if (to.meta.requiresAdmin && !userStore.user?.is_admin) {
    next('/')
  } else {
    next()
  }
})

export default router
