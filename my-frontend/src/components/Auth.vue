<template>
  <div class="auth">
    <h2>Auth</h2>

    <div v-if="!token">
      <input v-model="name" placeholder="Name" />
      <input v-model="email" placeholder="Email" />
      <input v-model="password" placeholder="Password" type="password" />

      <div class="buttons">
        <button @click="register">Register</button>
        <button @click="login">Login</button>
      </div>
    </div>

    <div v-else>
      <p>✅ You are logged in</p>
      <button @click="logout">Logout</button>
    </div>

    <p>{{ message }}</p>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const name = ref('')
const email = ref('')
const password = ref('')
const message = ref('')

const token = ref(localStorage.getItem('token'))

const API = 'http://127.0.0.1:8000/api'

const register = async () => {
  const res = await fetch(API + '/register', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      name: name.value,
      email: email.value,
      password: password.value
    })
  })

  const data = await res.json()
  message.value = data.message

  if (data.token) {
    localStorage.setItem('token', data.token)
    token.value = data.token
  }
}

const login = async () => {
  const res = await fetch(API + '/login', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      email: email.value,
      password: password.value
    })
  })

  const data = await res.json()
  message.value = data.message

  if (data.token) {
    localStorage.setItem('token', data.token)
    token.value = data.token
  }
}

const logout = async () => {
  await fetch(API + '/logout', {
    method: 'POST',
    headers: {
      'Authorization': 'Bearer ' + token.value
    }
  })

  localStorage.removeItem('token')
  token.value = null
  message.value = 'Logged out'
}
</script>

<style scoped>
.auth {
  margin: 20px;
}
input {
  display: block;
  margin: 5px 0;
  padding: 8px;
}
button {
  margin: 5px;
}
</style>