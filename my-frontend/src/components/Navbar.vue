<template>
  <header class="navbar">
    <router-link to="/home" class="logo">
      <span class="logo-icon">✈</span>
      TRAVEL APP
    </router-link>

    <nav class="nav-links">
      <router-link to="/home">SĀKUMS</router-link>
      <router-link to="/saved-plans">SAGLABĀTIE PLĀNI</router-link>
      <router-link to="/about">PAR PROJEKTU</router-link>
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
const currentTheme = ref('dark')

const applyTheme = (theme) => {
  document.body.classList.remove('dark', 'light')
  document.body.classList.add(theme)
}

onMounted(() => {
  const savedTheme = localStorage.getItem('theme') || 'dark'
  currentTheme.value = savedTheme
  applyTheme(savedTheme)
})

const toggleTheme = () => {
  currentTheme.value = currentTheme.value === 'dark' ? 'light' : 'dark'
  localStorage.setItem('theme', currentTheme.value)
  applyTheme(currentTheme.value)
}

const logout = () => {
  localStorage.removeItem('token')
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
}

.logo-icon {
  color: #8b7dff;
}

.nav-links {
  display: flex;
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

@media (max-width: 850px) {
  .navbar {
    height: auto;
    padding: 18px;
    flex-direction: column;
    gap: 16px;
  }

  .nav-links {
    flex-wrap: wrap;
    justify-content: center;
  }
}
</style>