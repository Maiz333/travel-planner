import { createRouter, createWebHistory } from 'vue-router'

import Login from '../views/Login.vue'
import Register from '../views/Register.vue'
import Home from '../views/Home.vue'
import SavedPlans from '../views/SavedPlans.vue'
import About from '../views/About.vue'
import Admin from '../views/Admin.vue'

const routes = [
  {
    path: '/',
    redirect: '/login'
  },
  {
    path: '/login',
    component: Login
  },
  {
    path: '/register',
    component: Register
  },
  {
    path: '/home',
    component: Home,
    meta: { requiresAuth: true }
  },
  {
    path: '/saved-plans',
    component: SavedPlans,
    meta: { requiresAuth: true }
  },
  {
    path: '/about',
    component: About,
    meta: { requiresAuth: true }
  },
  {
    path: '/admin',
    component: Admin,
    meta: { requiresAuth: true }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to) => {
  const token = localStorage.getItem('token')

  if (to.meta.requiresAuth && !token) {
    return '/login'
  }

  if ((to.path === '/login' || to.path === '/register') && token) {
    return '/home'
  }
})

export default router