<template>
  <header class="navbar">
    <router-link to="/home" class="logo">
      <span class="logo-icon">✈</span>
      TRAVEL APP
    </router-link>

    <nav class="nav-links">
      <router-link to="/home">SĀKUMS</router-link>

      <router-link to="/saved-plans">
        SAGLABĀTIE PLĀNI
      </router-link>

      <router-link to="/about">
        PAR PROJEKTU
      </router-link>

      <router-link
        v-if="isAdmin"
        to="/admin"
        class="admin-link"
      >
        ADMIN
      </router-link>
    </nav>

    <div class="actions">
      <button class="theme-btn" @click="toggleTheme">
        {{ currentTheme === 'dark' ? '☀️' : '🌙' }}
      </button>

      <button class="logout-btn" @click="logout">
        IZLOGOTIES
      </button>
    </div>
  </header>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const API_URL = import.meta.env.VITE_API_URL || 'http://127.0.0.1:8000/api'

const currentTheme = ref('dark')
const isAdmin = ref(false)

const applyTheme = (theme) => {
  document.body.classList.remove('dark', 'light')
  document.body.classList.add(theme)
}

onMounted(() => {
  const savedTheme = localStorage.getItem('theme') || 'dark'
  currentTheme.value = savedTheme
  applyTheme(savedTheme)

  loadUser()
})

const toggleTheme = () => {
  currentTheme.value = currentTheme.value === 'dark' ? 'light' : 'dark'
  localStorage.setItem('theme', currentTheme.value)
  applyTheme(currentTheme.value)
}

const loadUser = async () => {
  const token = localStorage.getItem('token')

  if (!token) {
    isAdmin.value = false
    return
  }

  try {
    const res = await fetch(`${API_URL}/user`, {
      headers: {
        Authorization: 'Bearer ' + token
      }
    })

    if (!res.ok) {
      isAdmin.value = false
      return
    }

    const data = await res.json()
    isAdmin.value = data.user?.role === 'admin'
  } catch (error) {
    isAdmin.value = false
  }
}

const logout = () => {
  localStorage.removeItem('token')
  isAdmin.value = false
  router.push('/login')
}
</script>

<style scoped>
.navbar {
  height: 84px;
  background: var(--nav-bg);
  border-bottom: 1px solid var(--border-color);
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 42px;
  position: sticky;
  top: 0;
  z-index: 1000;
  backdrop-filter: blur(18px);
}

.logo {
  color: var(--text-main);
  font-size: 24px;
  font-weight: 900;
  letter-spacing: 2px;
  text-decoration: none;
  display: flex;
  align-items: center;
  gap: 10px;
  white-space: nowrap;
}

.logo-icon {
  color: #8b7dff;
}

.nav-links {
  display: flex;
  align-items: center;
  gap: 30px;
}

.nav-links a {
  color: var(--text-muted);
  text-decoration: none;
  font-weight: 800;
  padding: 10px 0;
}

.nav-links a:hover,
.nav-links .router-link-active {
  color: #7b68ff;
}

.admin-link {
  padding: 8px 13px !important;
  border-radius: 999px;
  background: rgba(32, 201, 151, 0.16);
  color: #20c997 !important;
}

.actions {
  display: flex;
  align-items: center;
  gap: 12px;
}

.theme-btn {
  border: 1px solid var(--border-color);
  background: var(--card-bg);
  color: var(--text-main);
  width: 46px;
  height: 46px;
  border-radius: 14px;
  cursor: pointer;
  font-size: 18px;
  box-shadow: var(--shadow);
}

.logout-btn {
  border: none;
  background: linear-gradient(135deg, #ff4d6d, #ff758f);
  color: white;
  padding: 14px 20px;
  border-radius: 14px;
  cursor: pointer;
  font-weight: 800;
}

@media (max-width: 1050px) {
  .navbar {
    height: auto;
    padding: 18px;
    flex-direction: column;
    gap: 16px;
  }

  .nav-links {
    flex-wrap: wrap;
    justify-content: center;
    gap: 18px;
  }
}

@media (max-width: 560px) {
  .nav-links {
    flex-direction: column;
    width: 100%;
    gap: 10px;
  }

  .nav-links a {
    width: 100%;
    text-align: center;
    padding: 12px;
    border-radius: 14px;
    background: var(--card-bg);
  }

  .actions {
    width: 100%;
    flex-direction: column;
  }

  .theme-btn,
  .logout-btn {
    width: 100%;
  }

  .logo {
    font-size: 20px;
  }
}
</style>