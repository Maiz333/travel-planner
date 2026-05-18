<template>
  <section class="auth-bar">
    <div class="auth-card">
      <div>
        <p class="eyebrow">Autorizācija</p>
        <h2>{{ isLoggedIn ? 'Tu esi pieslēdzies' : 'Pieslēgties sistēmai' }}</h2>
        <p class="subtitle">
          {{ isLoggedIn ? 'Tagad vari saglabāt un pārvaldīt savus ceļojuma plānus.' : 'Izveido kontu vai pieslēdzies, lai saglabātu ceļojuma plānus.' }}
        </p>
      </div>

      <div v-if="!isLoggedIn" class="auth-form">
        <input
          v-model="name"
          class="auth-input"
          type="text"
          placeholder="Vārds"
        />

        <input
          v-model="email"
          class="auth-input"
          type="email"
          placeholder="E-pasts"
        />

        <input
          v-model="password"
          class="auth-input"
          type="password"
          placeholder="Parole"
        />

        <div class="auth-actions">
          <button class="auth-btn" @click="register">
            Reģistrēties
          </button>

          <button class="auth-btn secondary" @click="login">
            Pieslēgties
          </button>
        </div>
      </div>

      <div v-else class="logged-box">
        <div class="user-badge">
          ✅ Tu esi pieslēdzies
        </div>

        <button class="auth-btn danger" @click="logout">
          Iziet
        </button>
      </div>
    </div>

    <p v-if="message" :class="messageType">
      {{ message }}
    </p>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const name = ref('')
const email = ref('')
const password = ref('')
const message = ref('')
const messageType = ref('message')
const isLoggedIn = ref(false)

onMounted(() => {
  const token = localStorage.getItem('token')
  isLoggedIn.value = !!token
})

const setMessage = (text, type = 'message') => {
  message.value = text
  messageType.value = type
}

const register = async () => {
  setMessage('')

  if (!name.value || !email.value || !password.value) {
    setMessage('Aizpildi visus laukus', 'error')
    return
  }

  if (password.value.length < 6) {
    setMessage('Parolei jābūt vismaz 6 simboliem', 'error')
    return
  }

  try {
    const res = await fetch('http://127.0.0.1:8000/api/register', {
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

    const data = await res.json()

    if (!res.ok) {
      setMessage(data.message || 'Reģistrācija neizdevās. Iespējams, e-pasts jau tiek izmantots.', 'error')
      return
    }

    localStorage.setItem('token', data.token)
    isLoggedIn.value = true
    setMessage('Reģistrācija veiksmīga', 'success')
  } catch (error) {
    setMessage('Nevar pieslēgties backend serverim', 'error')
  }
}

const login = async () => {
  setMessage('')

  if (!email.value || !password.value) {
    setMessage('Ievadi e-pastu un paroli', 'error')
    return
  }

  try {
    const res = await fetch('http://127.0.0.1:8000/api/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        email: email.value,
        password: password.value
      })
    })

    const data = await res.json()

    if (!res.ok) {
      setMessage(data.message || 'Nepareizs e-pasts vai parole', 'error')
      return
    }

    localStorage.setItem('token', data.token)
    isLoggedIn.value = true
    setMessage('Pieslēgšanās veiksmīga', 'success')
  } catch (error) {
    setMessage('Nevar pieslēgties backend serverim', 'error')
  }
}

const logout = () => {
  localStorage.removeItem('token')
  isLoggedIn.value = false
  setMessage('Tu izgāji no sistēmas', 'success')
}
</script>

<style scoped>
.auth-bar {
  background:
    radial-gradient(circle at top left, rgba(106, 90, 255, 0.18), transparent 30%),
    linear-gradient(135deg, #101223, #171927);
  padding: 24px 48px;
  color: white;
  border-bottom: 1px solid rgba(255, 255, 255, 0.12);
  font-family: Inter, Arial, sans-serif;
}

.auth-card {
  display: grid;
  grid-template-columns: 1fr 1.4fr;
  gap: 24px;
  align-items: center;
  max-width: 1400px;
  margin: 0 auto;
  background: rgba(255, 255, 255, 0.08);
  border: 1px solid rgba(255, 255, 255, 0.12);
  border-radius: 22px;
  padding: 22px;
  box-shadow: 0 18px 45px rgba(0, 0, 0, 0.28);
}

.eyebrow {
  color: #8ea0ff;
  text-transform: uppercase;
  letter-spacing: 3px;
  font-size: 12px;
  margin: 0 0 8px;
}

h2 {
  margin: 0;
  font-size: 28px;
}

.subtitle {
  margin: 10px 0 0;
  color: #b7bdd8;
  line-height: 1.5;
}

.auth-form {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr auto;
  gap: 12px;
  align-items: center;
}

.auth-input {
  width: 100%;
  padding: 13px 14px;
  border-radius: 14px;
  border: 1px solid rgba(255, 255, 255, 0.18);
  background: #101223;
  color: white;
  font-size: 15px;
}

.auth-input::placeholder {
  color: #8d94b8;
}

.auth-actions {
  display: flex;
  gap: 10px;
}

.auth-btn {
  border: none;
  border-radius: 14px;
  padding: 13px 16px;
  background: linear-gradient(135deg, #6a5aff, #8b7dff);
  color: white;
  cursor: pointer;
  font-weight: 700;
  white-space: nowrap;
}

.auth-btn:hover {
  opacity: 0.9;
}

.secondary {
  background: rgba(255, 255, 255, 0.14);
}

.danger {
  background: linear-gradient(135deg, #ff4d6d, #ff758f);
}

.logged-box {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  gap: 16px;
}

.user-badge {
  padding: 12px 16px;
  border-radius: 999px;
  background: rgba(125, 255, 178, 0.12);
  color: #7dffb2;
  font-weight: 700;
}

.message,
.success,
.error {
  max-width: 1400px;
  margin: 12px auto 0;
  font-weight: 700;
}

.success {
  color: #7dffb2;
}

.error {
  color: #ff758f;
}

@media (max-width: 1100px) {
  .auth-card {
    grid-template-columns: 1fr;
  }

  .auth-form {
    grid-template-columns: 1fr;
  }

  .auth-actions {
    flex-wrap: wrap;
  }

  .logged-box {
    justify-content: flex-start;
  }
}

@media (max-width: 700px) {
  .auth-bar {
    padding: 18px;
  }
}
</style>