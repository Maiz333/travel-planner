<template>
  <div class="page">
    <div class="hero">
      <div>
        <p class="eyebrow">Travel Planner</p>
        <h1>Build your perfect trip</h1>
        <p class="subtitle">
          Choose a city, explore hotels, restaurants and museums, then create your own travel plan.
        </p>
      </div>
    </div>

    <div class="layout">
      <section class="panel">
        <label class="label">Choose city</label>

        <select v-model="selectedCity" @change="loadPlaces" class="select">
          <option disabled value="">Choose city</option>
          <option v-for="city in cities" :key="city.id" :value="city.id">
            {{ city.name }}
          </option>
        </select>

        <h2>Places</h2>

        <div v-if="places.length === 0" class="empty">
          Select a city to see places.
        </div>

        <div class="cards">
          <div v-for="place in places" :key="place.id" class="card">
            <div>
              <span class="badge">{{ place.type }}</span>
              <h3>{{ place.name }}</h3>
            </div>

            <button class="btn" @click="addToPlan(place)">
              Add +
            </button>
          </div>
        </div>
      </section>

      <aside class="panel plan-panel">
        <h2>My Plan</h2>

        <div v-if="plan.length === 0" class="empty">
          Your travel plan is empty.
        </div>

        <ul class="plan-list">
          <li v-for="item in plan" :key="item.id">
            <span>{{ item.name }}</span>
            <small>{{ item.type }}</small>
          </li>
        </ul>

        <button
          class="save-btn"
          :disabled="plan.length === 0"
          @click="savePlan"
        >
          Save trip plan
        </button>

        <p v-if="saveMessage" class="success">
          {{ saveMessage }}
        </p>

        <div class="saved-header">
          <h2>Saved Plans</h2>
          <button class="small-btn" @click="loadSavedPlans">
            Refresh
          </button>
        </div>

        <div v-if="savedPlans.length === 0" class="empty">
          No saved plans yet.
        </div>

        <div class="saved-plans">
          <div v-for="savedPlan in savedPlans" :key="savedPlan.id" class="saved-card">
            <div>
              <h3>{{ savedPlan.title }}</h3>
              <small>{{ formatDate(savedPlan.created_at) }}</small>
            </div>

            <div class="saved-actions">
              <button class="small-btn" @click="loadPlan(savedPlan)">
                Load
              </button>

              <button class="small-btn danger-btn" @click="deletePlan(savedPlan.id)">
                Delete
              </button>
            </div>
          </div>
        </div>
      </aside>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const cities = ref([])
const places = ref([])
const plan = ref([])
const savedPlans = ref([])
const selectedCity = ref('')
const saveMessage = ref('')

onMounted(async () => {
  await loadCities()
  await loadSavedPlans()
})

const loadCities = async () => {
  const res = await fetch('http://127.0.0.1:8000/api/cities')
  cities.value = await res.json()
}

const loadPlaces = async () => {
  const res = await fetch(
    `http://127.0.0.1:8000/api/places/${selectedCity.value}`
  )
  places.value = await res.json()
}

const addToPlan = (place) => {
  if (!plan.value.find((item) => item.id === place.id)) {
    plan.value.push(place)
  }
}

const savePlan = async () => {
  const token = localStorage.getItem('token')

  if (!token) {
    saveMessage.value = 'Please login before saving your trip plan'
    return
  }

  const res = await fetch('http://127.0.0.1:8000/api/plans', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': 'Bearer ' + token
    },
    body: JSON.stringify({
      title: 'My travel plan',
      places: plan.value
    })
  })

  const data = await res.json()

  if (!res.ok) {
    saveMessage.value = data.message || 'Error while saving plan'
    return
  }

  saveMessage.value = data.message
  await loadSavedPlans()
}

const loadSavedPlans = async () => {
  const token = localStorage.getItem('token')

  if (!token) {
    savedPlans.value = []
    return
  }

  const res = await fetch('http://127.0.0.1:8000/api/my-plans', {
    headers: {
      'Authorization': 'Bearer ' + token
    }
  })

  if (!res.ok) {
    savedPlans.value = []
    return
  }

  savedPlans.value = await res.json()
}

const loadPlan = (savedPlan) => {
  try {
    plan.value = JSON.parse(savedPlan.places)
    saveMessage.value = 'Plan loaded'
  } catch (error) {
    saveMessage.value = 'Could not load plan'
  }
}

const deletePlan = async (planId) => {
  const token = localStorage.getItem('token')

  if (!token) {
    saveMessage.value = 'Please login before deleting a trip plan'
    return
  }

  const res = await fetch(`http://127.0.0.1:8000/api/plans/${planId}`, {
    method: 'DELETE',
    headers: {
      'Authorization': 'Bearer ' + token
    }
  })

  const data = await res.json()

  if (!res.ok) {
    saveMessage.value = data.message || 'Error while deleting plan'
    return
  }

  saveMessage.value = data.message
  await loadSavedPlans()
}

const formatDate = (date) => {
  if (!date) return ''
  return new Date(date).toLocaleString()
}
</script>

<style scoped>
.page {
  min-height: 100vh;
  background:
    radial-gradient(circle at top left, rgba(106, 90, 255, 0.25), transparent 30%),
    linear-gradient(135deg, #0f1020, #171927);
  color: #ffffff;
  padding: 48px;
  font-family: Inter, Arial, sans-serif;
}

.hero {
  max-width: 900px;
  margin-bottom: 36px;
}

.eyebrow {
  color: #8ea0ff;
  text-transform: uppercase;
  letter-spacing: 3px;
  font-size: 13px;
  margin-bottom: 10px;
}

h1 {
  font-size: 54px;
  margin: 0;
  line-height: 1.05;
}

.subtitle {
  color: #b7bdd8;
  font-size: 18px;
  max-width: 720px;
  margin-top: 18px;
}

.layout {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 24px;
}

.panel {
  background: rgba(255, 255, 255, 0.08);
  border: 1px solid rgba(255, 255, 255, 0.12);
  border-radius: 24px;
  padding: 28px;
  backdrop-filter: blur(18px);
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35);
}

.label {
  display: block;
  margin-bottom: 10px;
  color: #c6caff;
  font-size: 14px;
}

.select {
  width: 100%;
  padding: 14px 16px;
  border-radius: 14px;
  border: 1px solid rgba(255, 255, 255, 0.18);
  background: #101223;
  color: white;
  font-size: 16px;
  margin-bottom: 28px;
}

h2 {
  margin: 0 0 18px;
  font-size: 28px;
}

.cards {
  display: grid;
  gap: 16px;
}

.card {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: rgba(15, 16, 32, 0.82);
  border: 1px solid rgba(255, 255, 255, 0.1);
  padding: 20px;
  border-radius: 18px;
}

.card h3 {
  margin: 10px 0 0;
  font-size: 20px;
}

.badge {
  display: inline-block;
  padding: 6px 10px;
  border-radius: 999px;
  background: rgba(142, 160, 255, 0.18);
  color: #aeb8ff;
  font-size: 12px;
  text-transform: uppercase;
}

.btn,
.save-btn,
.small-btn {
  border: none;
  border-radius: 14px;
  padding: 12px 18px;
  background: linear-gradient(135deg, #6a5aff, #8b7dff);
  color: white;
  cursor: pointer;
  font-weight: 700;
}

.btn:hover,
.save-btn:hover,
.small-btn:hover {
  opacity: 0.9;
}

.small-btn {
  padding: 8px 12px;
  font-size: 13px;
}

.danger-btn {
  background: linear-gradient(135deg, #ff4d6d, #ff758f);
}

.plan-panel {
  position: sticky;
  top: 24px;
  height: fit-content;
}

.plan-list {
  list-style: none;
  padding: 0;
  margin: 0 0 22px;
}

.plan-list li {
  display: flex;
  justify-content: space-between;
  gap: 12px;
  padding: 14px 0;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.plan-list small {
  color: #9ba5d9;
}

.empty {
  color: #9299b8;
  padding: 18px 0;
}

.save-btn {
  width: 100%;
}

.save-btn:disabled {
  opacity: 0.45;
  cursor: not-allowed;
}

.success {
  color: #7dffb2;
  margin-top: 14px;
  font-weight: 700;
  text-align: center;
}

.saved-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 34px;
}

.saved-header h2 {
  margin: 0;
}

.saved-plans {
  display: grid;
  gap: 12px;
}

.saved-card {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  background: rgba(15, 16, 32, 0.72);
  border: 1px solid rgba(255, 255, 255, 0.09);
  border-radius: 16px;
  padding: 16px;
}

.saved-card h3 {
  margin: 0 0 6px;
  font-size: 16px;
}

.saved-card small {
  color: #9ba5d9;
}

.saved-actions {
  display: flex;
  gap: 8px;
  align-items: center;
}

@media (max-width: 900px) {
  .page {
    padding: 24px;
  }

  h1 {
    font-size: 38px;
  }

  .layout {
    grid-template-columns: 1fr;
  }

  .saved-card {
    align-items: flex-start;
    flex-direction: column;
  }
}
</style>