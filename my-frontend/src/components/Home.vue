<template>
  <div class="page">
    <div class="hero">
      <div>
        <p class="eyebrow">Ceļojumu plānotājs</p>
        <h1>Izveido savu ceļojuma plānu</h1>
        <p class="subtitle">
          Izvēlies pilsētu, apskati viesnīcas, restorānus un muzejus, pievieno vietas plānam un saglabā to.
        </p>
      </div>
    </div>

    <div class="layout">
      <section class="panel">
        <label class="label">Izvēlies pilsētu</label>

        <select v-model="selectedCity" @change="loadPlaces" class="select">
          <option disabled value="">Izvēlies pilsētu</option>
          <option v-for="city in cities" :key="city.id" :value="city.id">
            {{ city.name }}
          </option>
        </select>

        <div class="tools">
          <input
            v-model="searchText"
            class="input"
            type="text"
            placeholder="Meklēt vietu..."
          />

          <select v-model="selectedType" class="select small-select">
            <option value="all">Visi tipi</option>
            <option value="hotel">Viesnīcas</option>
            <option value="restaurant">Restorāni</option>
            <option value="museum">Muzeji</option>
          </select>

          <select v-model="sortType" class="select small-select">
            <option value="default">Bez kārtošanas</option>
            <option value="az">A-Z</option>
            <option value="za">Z-A</option>
          </select>
        </div>

        <div class="stats">
          <div class="stat-card">
            <strong>{{ places.length }}</strong>
            <span>Vietas kopā</span>
          </div>

          <div class="stat-card">
            <strong>{{ countByType('hotel') }}</strong>
            <span>Viesnīcas</span>
          </div>

          <div class="stat-card">
            <strong>{{ countByType('restaurant') }}</strong>
            <span>Restorāni</span>
          </div>

          <div class="stat-card">
            <strong>{{ countByType('museum') }}</strong>
            <span>Muzeji</span>
          </div>
        </div>

        <h2>Vietas</h2>

        <div v-if="filteredPlaces.length === 0" class="empty">
          Nav atrasta neviena vieta.
        </div>

        <div class="cards">
          <div v-for="place in filteredPlaces" :key="place.id" class="card">
            <div>
              <span class="badge">{{ translateType(place.type) }}</span>
              <h3>{{ place.name }}</h3>
            </div>

            <button class="btn" @click="addToPlan(place)">
              Pievienot +
            </button>
          </div>
        </div>

        <div class="map-section">
          <div class="map-title">
            <h2>Karte</h2>

            <button class="small-btn" :disabled="plan.length === 0" @click="focusPlan">
              Rādīt plānu kartē
            </button>
          </div>

          <div class="map-wrapper">
            <l-map
              ref="mapRef"
              :zoom="mapZoom"
              :center="mapCenter"
              class="map"
            >
              <l-tile-layer
                url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
                attribution="&copy; OpenStreetMap contributors"
              />

              <l-marker
                v-for="place in filteredPlaces"
                :key="'place-' + place.id"
                :lat-lng="getPlaceCoordinates(place)"
              >
                <l-popup>
                  <strong>{{ place.name }}</strong><br />
                  {{ translateType(place.type) }}
                </l-popup>
              </l-marker>

              <l-marker
                v-for="item in plan"
                :key="'selected-' + item.id"
                :lat-lng="getPlaceCoordinates(item)"
              >
                <l-popup>
                  <strong>⭐ {{ item.name }}</strong><br />
                  Pievienots ceļojuma plānam
                </l-popup>
              </l-marker>
            </l-map>
          </div>
        </div>
      </section>

      <aside class="panel plan-panel">
        <h2>Mans plāns</h2>

        <label class="label">Plāna nosaukums</label>
        <input
          v-model="planTitle"
          class="input"
          type="text"
          placeholder="Ievadi plāna nosaukumu"
        />

        <div v-if="plan.length === 0" class="empty">
          Tavs ceļojuma plāns ir tukšs.
        </div>

        <ul class="plan-list">
          <li v-for="item in plan" :key="item.id">
            <div>
              <span>{{ item.name }}</span>
              <small>{{ translateType(item.type) }}</small>
            </div>

            <button class="remove-btn" @click="removeFromPlan(item.id)">
              Noņemt
            </button>
          </li>
        </ul>

        <button
          class="save-btn"
          :disabled="plan.length === 0"
          @click="savePlan"
        >
          Saglabāt plānu
        </button>

        <p v-if="saveMessage" class="success">
          {{ saveMessage }}
        </p>

        <div class="saved-header">
          <h2>Saglabātie plāni</h2>

          <button class="small-btn" @click="loadSavedPlans">
            Atjaunot
          </button>
        </div>

        <div v-if="savedPlans.length === 0" class="empty">
          Saglabātu plānu vēl nav.
        </div>

        <div class="saved-plans">
          <div v-for="savedPlan in savedPlans" :key="savedPlan.id" class="saved-card">
            <div>
              <h3>{{ savedPlan.title }}</h3>
              <small>{{ formatDate(savedPlan.created_at) }}</small>
            </div>

            <div class="saved-actions">
              <button class="small-btn" @click="loadPlan(savedPlan)">
                Ielādēt
              </button>

              <button class="small-btn danger-btn" @click="deletePlan(savedPlan.id)">
                Dzēst
              </button>
            </div>
          </div>
        </div>
      </aside>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'

import 'leaflet/dist/leaflet.css'
import {
  LMap,
  LTileLayer,
  LMarker,
  LPopup
} from '@vue-leaflet/vue-leaflet'

const cities = ref([])
const places = ref([])
const plan = ref([])
const savedPlans = ref([])

const selectedCity = ref('')
const selectedType = ref('all')
const sortType = ref('default')
const searchText = ref('')
const saveMessage = ref('')
const planTitle = ref('Mans ceļojuma plāns')

const mapRef = ref(null)

const cityCoordinates = {
  1: [48.8566, 2.3522],
  2: [51.5074, -0.1278],
  3: [35.6762, 139.6503],
  4: [41.9028, 12.4964]
}

const placeCoordinates = {
  'Hotel Paris': [48.8566, 2.3422],
  'Paris Restaurant': [48.8616, 2.3522],
  'Louvre Museum': [48.8606, 2.3376],

  'London Hotel': [51.5074, -0.1278],
  'London Restaurant': [51.5124, -0.1188],
  'British Museum': [51.5194, -0.1270],

  'Tokyo Hotel': [35.6762, 139.6503],
  'Tokyo Sushi Restaurant': [35.6895, 139.6917],
  'Tokyo National Museum': [35.7188, 139.7765],

  'Rome Hotel': [41.9028, 12.4964],
  'Rome Restaurant': [41.8955, 12.4823],
  'Colosseum Museum': [41.8902, 12.4922]
}

const mapCenter = computed(() => {
  if (selectedCity.value && cityCoordinates[selectedCity.value]) {
    return cityCoordinates[selectedCity.value]
  }

  return [50, 10]
})

const mapZoom = computed(() => {
  return selectedCity.value ? 12 : 5
})

const filteredPlaces = computed(() => {
  let result = [...places.value]

  if (selectedType.value !== 'all') {
    result = result.filter((place) => place.type === selectedType.value)
  }

  if (searchText.value.trim() !== '') {
    const search = searchText.value.toLowerCase()

    result = result.filter((place) =>
      place.name.toLowerCase().includes(search)
    )
  }

  if (sortType.value === 'az') {
    result.sort((a, b) => a.name.localeCompare(b.name))
  }

  if (sortType.value === 'za') {
    result.sort((a, b) => b.name.localeCompare(a.name))
  }

  return result
})

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
  searchText.value = ''
  selectedType.value = 'all'
  sortType.value = 'default'
}

const translateType = (type) => {
  if (type === 'hotel') return 'Viesnīca'
  if (type === 'restaurant') return 'Restorāns'
  if (type === 'museum') return 'Muzejs'

  return type
}

const countByType = (type) => {
  return places.value.filter((place) => place.type === type).length
}

const getPlaceCoordinates = (place) => {
  return placeCoordinates[place.name] || cityCoordinates[place.city_id] || [50, 10]
}

const focusMapOnPlace = (place) => {
  const coordinates = getPlaceCoordinates(place)

  if (mapRef.value?.leafletObject) {
    mapRef.value.leafletObject.setView(coordinates, 14)
  }
}

const focusPlan = () => {
  if (plan.value.length === 0) return

  focusMapOnPlace(plan.value[0])
}

const addToPlan = (place) => {
  if (!plan.value.find((item) => item.id === place.id)) {
    plan.value.push(place)
    focusMapOnPlace(place)
  }
}

const removeFromPlan = (placeId) => {
  plan.value = plan.value.filter((item) => item.id !== placeId)
  saveMessage.value = 'Vieta noņemta no plāna'
}

const savePlan = async () => {
  const token = localStorage.getItem('token')

  if (!token) {
    saveMessage.value = 'Lūdzu, pieslēdzies pirms plāna saglabāšanas'
    return
  }

  const res = await fetch('http://127.0.0.1:8000/api/plans', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': 'Bearer ' + token
    },
    body: JSON.stringify({
      title: planTitle.value || 'Mans ceļojuma plāns',
      places: plan.value
    })
  })

  const data = await res.json()

  if (!res.ok) {
    saveMessage.value = data.message || 'Kļūda saglabājot plānu'
    return
  }

  saveMessage.value = 'Ceļojuma plāns saglabāts'
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
    planTitle.value = savedPlan.title
    saveMessage.value = 'Plāns ielādēts'

    if (plan.value.length > 0) {
      focusMapOnPlace(plan.value[0])
    }
  } catch (error) {
    saveMessage.value = 'Neizdevās ielādēt plānu'
  }
}

const deletePlan = async (planId) => {
  const token = localStorage.getItem('token')

  if (!token) {
    saveMessage.value = 'Lūdzu, pieslēdzies pirms plāna dzēšanas'
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
    saveMessage.value = data.message || 'Kļūda dzēšot plānu'
    return
  }

  saveMessage.value = 'Plāns izdzēsts'
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

.select,
.input {
  width: 100%;
  padding: 14px 16px;
  border-radius: 14px;
  border: 1px solid rgba(255, 255, 255, 0.18);
  background: #101223;
  color: white;
  font-size: 16px;
}

.select {
  margin-bottom: 28px;
}

.input {
  margin-bottom: 16px;
}

.tools {
  display: grid;
  grid-template-columns: 1fr 220px 220px;
  gap: 14px;
  margin-bottom: 20px;
}

.tools .select,
.tools .input {
  margin-bottom: 0;
}

.small-select {
  min-width: 180px;
}

.stats {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 14px;
  margin-bottom: 28px;
}

.stat-card {
  background: rgba(15, 16, 32, 0.72);
  border: 1px solid rgba(255, 255, 255, 0.09);
  border-radius: 16px;
  padding: 16px;
}

.stat-card strong {
  display: block;
  font-size: 28px;
  margin-bottom: 4px;
}

.stat-card span {
  color: #aeb8ff;
  font-size: 13px;
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
.small-btn,
.remove-btn {
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
.small-btn:hover,
.remove-btn:hover {
  opacity: 0.9;
}

.small-btn {
  padding: 8px 12px;
  font-size: 13px;
}

.small-btn:disabled {
  opacity: 0.45;
  cursor: not-allowed;
}

.remove-btn {
  padding: 8px 10px;
  font-size: 12px;
  background: rgba(255, 255, 255, 0.12);
}

.danger-btn {
  background: linear-gradient(135deg, #ff4d6d, #ff758f);
}

.map-section {
  margin-top: 28px;
}

.map-title {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.map-wrapper {
  overflow: hidden;
  border-radius: 20px;
  height: 360px;
  border: 1px solid rgba(255, 255, 255, 0.14);
  box-shadow: 0 18px 45px rgba(0, 0, 0, 0.28);
}

.map {
  height: 360px;
  width: 100%;
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
  align-items: center;
  gap: 12px;
  padding: 14px 0;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.plan-list small {
  display: block;
  color: #9ba5d9;
  margin-top: 4px;
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

@media (max-width: 1100px) {
  .tools {
    grid-template-columns: 1fr;
  }

  .stats {
    grid-template-columns: repeat(2, 1fr);
  }
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