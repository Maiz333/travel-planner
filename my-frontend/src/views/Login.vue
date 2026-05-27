<template>
  <div class="auth-page">
    <button class="theme-toggle" @click="toggleTheme">
      {{ currentTheme === 'dark' ? '☀️' : '🌙' }}
    </button>

    <div class="auth-card">
      <p class="eyebrow">Ceļojumu plānotājs</p>

      <h1>Pieslēgties sistēmai</h1>

      <p class="subtitle">
        Ievadi savu e-pastu un paroli, lai turpinātu ceļojuma plānošanu.
      </p>

      <input
        v-model="email"
        class="input"
        type="email"
        placeholder="E-pasts"
      />

      <input
        v-model="password"
        class="input"
        type="password"
        placeholder="Parole"
      />

      <button class="main-btn" @click="login">
        Pieslēgties
      </button>

      <p v-if="message" class="error">
        {{ message }}
      </p>

      <p class="bottom-text">
        Nav konta?
        <router-link to="/register">
          Reģistrēties
        </router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const email = ref('')
const password = ref('')
const message = ref('')
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

const login = async () => {
  message.value = ''

  if (!email.value || !password.value) {
    message.value = 'Ievadi e-pastu un paroli'
    return
  }

  try {
    const response = await fetch('http://127.0.0.1:8000/api/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        email: email.value,
        password: password.value
      })
    })

    const data = await response.json()

    if (!response.ok) {
      message.value = 'Nepareizs e-pasts vai parole'
      return
    }

    localStorage.setItem('token', data.token)
    router.push('/home')
  } catch (error) {
    message.value = 'Backend nav pieejams'
  }
}
</script>

<style scoped>
.auth-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 32px;
  background: var(--page-gradient);
  color: var(--text-main);
  position: relative;
}

.theme-toggle {
  position: absolute;
  top: 28px;
  right: 32px;
  width: 48px;
  height: 48px;
  border-radius: 16px;
  border: 1px solid var(--border-color);
  background: var(--card-bg);
  color: var(--text-main);
  cursor: pointer;
  font-size: 18px;
  box-shadow: var(--shadow);
}

.auth-card {
  width: 100%;
  max-width: 460px;
  background: var(--card-bg);
  border: 1px solid var(--border-color);
  border-radius: 28px;
  padding: 36px;
  box-shadow: var(--shadow);
}

.eyebrow {
  color: #8ea0ff;
  text-transform: uppercase;
  letter-spacing: 3px;
  font-size: 13px;
  margin: 0 0 12px;
}

h1 {
  font-size: 38px;
  margin: 0 0 12px;
  color: var(--text-main);
}

.subtitle {
  color: var(--text-muted);
  line-height: 1.5;
  margin-bottom: 26px;
}

.input {
  width: 100%;
  padding: 15px 16px;
  margin-bottom: 14px;
  border-radius: 15px;
  border: 1px solid var(--border-color);
  background: var(--input-bg);
  color: var(--text-main);
  font-size: 16px;
}

.input::placeholder {
  color: var(--text-soft);
}

.main-btn {
  width: 100%;
  padding: 15px 18px;
  border: none;
  border-radius: 15px;
  background: linear-gradient(135deg, #6a5aff, #8b7dff);
  color: white;
  font-weight: 800;
  cursor: pointer;
  font-size: 16px;
}

.error {
  color: #ff5d7d;
  font-weight: 800;
  margin-top: 16px;
}

.bottom-text {
  color: var(--text-muted);
  margin-top: 22px;
  text-align: center;
}

.bottom-text a {
  color: #7b68ff;
  font-weight: 800;
  text-decoration: none;
}
</style>