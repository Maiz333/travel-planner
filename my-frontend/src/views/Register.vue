<template>
  <div class="auth-page">
    <button class="theme-toggle" @click="toggleTheme">
      {{ currentTheme === 'dark' ? '☀️' : '🌙' }}
    </button>

    <div class="auth-card">
      <p class="eyebrow">Ceļojumu plānotājs</p>

      <h1>Izveidot kontu</h1>

      <p class="subtitle">
        Reģistrējies, lai varētu saglabāt un pārvaldīt savus ceļojuma plānus.
      </p>

      <div class="form-group">
        <label>Vārds</label>
        <input
          v-model.trim="name"
          class="input"
          type="text"
          placeholder="Tavs vārds"
          autocomplete="name"
        />
      </div>

      <div class="form-group">
        <label>E-pasts</label>
        <input
          v-model.trim="email"
          class="input"
          type="email"
          placeholder="piemers@email.com"
          autocomplete="email"
        />
      </div>

      <div class="form-group">
        <label>Parole</label>
        <input
          v-model="password"
          class="input"
          type="password"
          placeholder="Vismaz 6 simboli"
          autocomplete="new-password"
          @keyup.enter="register"
        />
      </div>

      <button
        class="main-btn"
        :disabled="isLoading"
        @click="register"
      >
        {{ isLoading ? 'Reģistrē...' : 'Reģistrēties' }}
      </button>

      <p v-if="message" class="error">
        {{ message }}
      </p>

      <p v-if="successMessage" class="success">
        {{ successMessage }}
      </p>

      <p class="bottom-text">
        Konts jau ir?
        <router-link to="/login">
          Pieslēgties
        </router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const API_URL = import.meta.env.VITE_API_URL || 'http://127.0.0.1:8000/api'

const name = ref('')
const email = ref('')
const password = ref('')
const message = ref('')
const successMessage = ref('')
const currentTheme = ref('dark')
const isLoading = ref(false)

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

const isValidEmail = (value) => {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)
}

const register = async () => {
  message.value = ''
  successMessage.value = ''

  if (isLoading.value) return

  if (!name.value || !email.value || !password.value) {
    message.value = 'Aizpildi visus laukus'
    return
  }

  if (name.value.length < 2) {
    message.value = 'Vārdam jābūt vismaz 2 simboliem'
    return
  }

  if (!isValidEmail(email.value)) {
    message.value = 'Ievadi korektu e-pasta adresi'
    return
  }

  if (password.value.length < 6) {
    message.value = 'Parolei jābūt vismaz 6 simboliem'
    return
  }

  isLoading.value = true

  try {
    const response = await fetch(`${API_URL}/register`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        name: name.value,
        email: email.value,
        password: password.value
      })
    })

    let data = {}

    try {
      data = await response.json()
    } catch {
      data = {}
    }

    if (!response.ok) {
      if (data.errors?.email) {
        message.value = 'Šis e-pasts jau ir reģistrēts'
        return
      }

      if (data.errors?.password) {
        message.value = data.errors.password[0]
        return
      }

      if (data.errors?.name) {
        message.value = data.errors.name[0]
        return
      }

      message.value = data.message || 'Reģistrācija neizdevās'
      return
    }

    if (!data.token) {
      message.value = 'Serveris neatgrieza autorizācijas tokenu'
      return
    }

    localStorage.setItem('token', data.token)
    successMessage.value = 'Konts veiksmīgi izveidots'

    setTimeout(() => {
      router.push('/home')
    }, 350)
  } catch (error) {
    message.value = 'Backend nav pieejams. Pārbaudi, vai Laravel serveris ir ieslēgts.'
  } finally {
    isLoading.value = false
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
  max-width: 470px;
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

.form-group {
  margin-bottom: 14px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  color: #8ea0ff;
  font-size: 14px;
  font-weight: 800;
}

.input {
  width: 100%;
  padding: 15px 16px;
  border-radius: 15px;
  border: 1px solid var(--border-color);
  background: var(--input-bg);
  color: var(--text-main);
  font-size: 16px;
  outline: none;
}

.input:focus {
  border-color: #8b7dff;
  box-shadow: 0 0 0 4px rgba(139, 125, 255, 0.16);
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
  margin-top: 4px;
}

.main-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.error,
.success {
  border-radius: 14px;
  padding: 12px 14px;
  font-weight: 800;
  margin-top: 16px;
  line-height: 1.4;
}

.error {
  color: #ff5d7d;
  background: rgba(255, 93, 125, 0.12);
  border: 1px solid rgba(255, 93, 125, 0.25);
}

.success {
  color: #20c997;
  background: rgba(32, 201, 151, 0.12);
  border: 1px solid rgba(32, 201, 151, 0.25);
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

.bottom-text a:hover {
  text-decoration: underline;
}

@media (max-width: 600px) {
  .auth-page {
    padding: 20px;
  }

  .theme-toggle {
    top: 18px;
    right: 18px;
  }

  .auth-card {
    padding: 26px;
    border-radius: 24px;
  }

  h1 {
    font-size: 31px;
  }
}
</style>