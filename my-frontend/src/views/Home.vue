<template>
  <Navbar />

  <div class="page">
    <div class="hero">
      <div>
        <p class="eyebrow">Ceļojumu plānotājs</p>

        <h1>Izveido savu ceļojuma plānu</h1>

        <p class="subtitle">
          Izvēlies pilsētu, apskati viesnīcas, restorānus un
          muzejus, pievieno vietas plānam un saglabā to.
        </p>
      </div>
    </div>

    <div class="layout">
      <section class="panel">
        <label class="label">Izvēlies pilsētu</label>

        <select
          v-model="selectedCity"
          @change="loadPlaces"
          class="select"
        >
          <option disabled value="">
            Izvēlies pilsētu
          </option>

          <option
            v-for="city in cities"
            :key="city.id"
            :value="city.id"
          >
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

          <select
            v-model="selectedType"
            class="select small-select"
          >
            <option value="all">Visi tipi</option>
            <option value="hotel">Viesnīcas</option>
            <option value="restaurant">Restorāni</option>
            <option value="museum">Muzeji</option>
          </select>

          <select
            v-model="sortType"
            class="select small-select"
          >
            <option value="default">Bez kārtošanas</option>
            <option value="az">A-Z</option>
            <option value="za">Z-A</option>
            <option value="rating">Labākais reitings</option>
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

        <div
          v-if="filteredPlaces.length === 0"
          class="empty"
        >
          Nav atrasta neviena vieta.
        </div>

        <div class="cards">
          <div
            v-for="place in filteredPlaces"
            :key="place.id"
            class="card place-card"
          >
            <img
              class="place-image"
              :src="getPlaceImage(place)"
              :alt="place.name"
              @error="setTypeFallback($event, place.type)"
            />

            <div class="place-info">
              <div class="card-top">
                <span class="badge">
                  {{ translateType(place.type) }}
                </span>

                <span class="rating">
                  ⭐ {{ place.rating || '4.5' }}
                </span>
              </div>

              <h3>{{ place.name }}</h3>

              <p class="description">
                {{
                  place.description ||
                  'Populāra vieta, ko vērts pievienot ceļojuma plānam.'
                }}
              </p>

              <button
                class="btn"
                @click="addToPlan(place)"
              >
                Pievienot +
              </button>
            </div>
          </div>
        </div>

        <div class="map-section">
          <div class="map-title">
            <h2>Karte</h2>

            <button
              class="small-btn"
              :disabled="plan.length === 0"
              @click="focusPlan"
            >
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

              <l-polyline
                v-if="planRoute.length >= 2"
                :lat-lngs="planRoute"
                color="#8b7dff"
                :weight="5"
              />

              <l-marker
                v-for="place in filteredPlaces"
                :key="'place-' + place.id"
                :lat-lng="getPlaceCoordinates(place)"
              >
                <l-popup>
                  <strong>{{ place.name }}</strong>
                  <br />
                  {{ translateType(place.type) }}
                  <br />
                  ⭐ {{ place.rating || '4.5' }}
                  <br />

                  <button
                    class="popup-btn"
                    @click="addToPlan(place)"
                  >
                    Pievienot plānam
                  </button>
                </l-popup>
              </l-marker>

              <l-marker
                v-for="item in plan"
                :key="'selected-' + item.id"
                :lat-lng="getPlaceCoordinates(item)"
              >
                <l-popup>
                  <strong>
                    ⭐ {{ item.name }}
                  </strong>
                  <br />
                  Pievienots ceļojuma plānam
                </l-popup>
              </l-marker>
            </l-map>
          </div>
        </div>
      </section>

      <aside class="panel plan-panel">
        <div class="plan-title-row">
          <h2>Mans plāns</h2>

          <span
            v-if="editingPlanId"
            class="editing-badge"
          >
            Rediģēšana
          </span>
        </div>

        <label class="label">
          Plāna nosaukums
        </label>

        <input
          v-model="planTitle"
          class="input"
          type="text"
          placeholder="Ievadi plāna nosaukumu"
        />

        <p
          v-if="editingPlanId"
          class="edit-note"
        >
          Tu rediģē saglabātu plānu. Vari mainīt nosaukumu, pievienot vai noņemt vietas un nospiest “Atjaunināt plānu”.
        </p>

        <div
          v-if="plan.length === 0"
          class="empty"
        >
          Tavs ceļojuma plāns ir tukšs.
        </div>

        <ul class="plan-list">
          <li
            v-for="item in plan"
            :key="item.id"
          >
            <div>
              <span>{{ item.name }}</span>

              <small>
                {{ translateType(item.type) }}
                · ⭐ {{ item.rating || '4.5' }}
              </small>
            </div>

            <button
              class="remove-btn"
              @click="removeFromPlan(item.id)"
            >
              Noņemt
            </button>
          </li>
        </ul>

        <div class="plan-actions">
          <button
            v-if="!editingPlanId"
            class="save-btn"
            :disabled="plan.length === 0"
            @click="savePlan"
          >
            Saglabāt plānu
          </button>

          <button
            v-if="editingPlanId"
            class="save-btn"
            :disabled="plan.length === 0"
            @click="updatePlan"
          >
            Atjaunināt plānu
          </button>

          <button
            v-if="editingPlanId"
            class="secondary-btn"
            @click="resetCurrentPlan"
          >
            Jauns plāns
          </button>
        </div>

        <p
          v-if="saveMessage"
          class="success"
        >
          {{ saveMessage }}
        </p>

        <div class="saved-header">
          <h2>Saglabātie plāni</h2>

          <button
            class="small-btn"
            @click="loadSavedPlans"
          >
            Atjaunot
          </button>
        </div>

        <div
          v-if="savedPlans.length === 0"
          class="empty"
        >
          Saglabātu plānu vēl nav.
        </div>

        <div class="saved-plans">
          <div
            v-for="savedPlan in savedPlans"
            :key="savedPlan.id"
            class="saved-card"
            :class="{ active: editingPlanId === savedPlan.id }"
          >
            <div>
              <h3>{{ savedPlan.title }}</h3>

              <small>
                {{ formatDate(savedPlan.created_at) }}
              </small>
            </div>

            <div class="saved-actions">
              <button
                class="small-btn"
                @click="loadPlan(savedPlan)"
              >
                Ielādēt
              </button>

              <button
                class="small-btn danger-btn"
                @click="deletePlan(savedPlan.id)"
              >
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
import {
  ref,
  onMounted,
  computed
} from 'vue'

import Navbar from '../components/Navbar.vue'

import 'leaflet/dist/leaflet.css'

import {
  LMap,
  LTileLayer,
  LMarker,
  LPopup,
  LPolyline
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
const editingPlanId = ref(null)

const mapRef = ref(null)

const fallbackImages = {
  hotel: 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1200&q=80',
  restaurant: 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1200&q=80',
  museum: 'https://images.unsplash.com/photo-1518998053901-5348d3961a04?auto=format&fit=crop&w=1200&q=80',
  default: 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=80'
}

const fixImage = (url) => {
  if (!url) return ''

  if (
    url.includes('images.unsplash.com') &&
    !url.includes('?')
  ) {
    return `${url}?auto=format&fit=crop&w=1200&q=80`
  }

  return url
}

const getPlaceImage = (place) => {
  return (
    fixImage(place.image_url) ||
    fallbackImages[place.type] ||
    fallbackImages.default
  )
}

const setTypeFallback = (event, type) => {
  event.target.src =
    fallbackImages[type] || fallbackImages.default
}

const cityCoordinates = {
  1: [48.8566, 2.3522],
  2: [51.5074, -0.1278],
  3: [35.6762, 139.6503],
  4: [41.9028, 12.4964],
  5: [52.52, 13.405],
  6: [41.3874, 2.1686]
}

const placeCoordinates = {
  'Hotel Paris Luxury': [48.8566, 2.3422],
  'Eiffel Grand Hotel': [48.8584, 2.2945],
  'Montmartre Boutique Hotel': [48.8867, 2.3431],
  'Paris Restaurant': [48.8616, 2.3522],
  'Le Gourmet Paris': [48.8656, 2.3212],
  'Seine Riverside Cafe': [48.8569, 2.3409],
  'Louvre Museum': [48.8606, 2.3376],
  'Modern Art Museum Paris': [48.8641, 2.2977],
  'Paris History Gallery': [48.853, 2.3499],

  'London Royal Hotel': [51.5074, -0.1278],
  'Big Ben Suites': [51.5007, -0.1246],
  'Thames View Hotel': [51.5033, -0.1195],
  'London Steak House': [51.5124, -0.1188],
  'British Restaurant': [51.509, -0.135],
  'Royal Tea Room': [51.515, -0.141],
  'British Museum': [51.5194, -0.1270],
  'London History Museum': [51.4967, -0.1764],
  'Science Museum London': [51.4978, -0.1745],

  'Tokyo Imperial Hotel': [35.6762, 139.6503],
  'Sakura Hotel': [35.6938, 139.7034],
  'Shibuya Sky Hotel': [35.658, 139.7016],
  'Tokyo Sushi Restaurant': [35.6895, 139.6917],
  'Ramen House Tokyo': [35.6984, 139.773],
  'Shinjuku Noodle Bar': [35.6938, 139.7034],
  'Tokyo National Museum': [35.7188, 139.7765],
  'Anime Museum': [35.704, 139.578],
  'Samurai Culture Museum': [35.694, 139.703],

  'Rome Palace Hotel': [41.9028, 12.4964],
  'Colosseum Suites': [41.8902, 12.4922],
  'Vatican Garden Hotel': [41.9022, 12.4539],
  'Rome Pizza Restaurant': [41.8955, 12.4823],
  'Italian Taste Rome': [41.9009, 12.4833],
  'Trastevere Pasta House': [41.8896, 12.4712],
  'Colosseum Museum': [41.8902, 12.4922],
  'Ancient Rome Museum': [41.8925, 12.4853],
  'Vatican Museum': [41.9065, 12.4536],

  'Berlin Central Hotel': [52.52, 13.405],
  'Berlin Wall Suites': [52.5351, 13.3903],
  'Alexanderplatz Hotel': [52.5219, 13.4132],
  'Berlin German Restaurant': [52.5159, 13.3777],
  'Schnitzel House Berlin': [52.5075, 13.3904],
  'Brandenburg Cafe': [52.5163, 13.3777],
  'Berlin History Museum': [52.5176, 13.3969],
  'Technology Museum Berlin': [52.4987, 13.3777],
  'Berlin Wall Museum': [52.5351, 13.3903],

  'Barcelona Beach Hotel': [41.3851, 2.1734],
  'Sagrada Hotel': [41.4036, 2.1744],
  'Gothic Quarter Hotel': [41.3839, 2.1763],
  'Barcelona Tapas Restaurant': [41.3818, 2.1685],
  'Seafood Barcelona': [41.3765, 2.1895],
  'Catalan Taste House': [41.3917, 2.1649],
  'Barcelona Art Museum': [41.3688, 2.1536],
  'Gaudi Museum': [41.4145, 2.1527],
  'Picasso Museum Barcelona': [41.3853, 2.1809]
}

const mapCenter = computed(() => {
  if (
    selectedCity.value &&
    cityCoordinates[selectedCity.value]
  ) {
    return cityCoordinates[selectedCity.value]
  }

  return [50, 10]
})

const mapZoom = computed(() => {
  return selectedCity.value ? 12 : 5
})

const planRoute = computed(() => {
  return plan.value.map((place) =>
    getPlaceCoordinates(place)
  )
})

const filteredPlaces = computed(() => {
  let result = [...places.value]

  if (selectedType.value !== 'all') {
    result = result.filter(
      (place) => place.type === selectedType.value
    )
  }

  if (searchText.value.trim() !== '') {
    const search = searchText.value.toLowerCase()

    result = result.filter((place) =>
      place.name.toLowerCase().includes(search)
    )
  }

  if (sortType.value === 'az') {
    result.sort((a, b) =>
      a.name.localeCompare(b.name)
    )
  }

  if (sortType.value === 'za') {
    result.sort((a, b) =>
      b.name.localeCompare(a.name)
    )
  }

  if (sortType.value === 'rating') {
    result.sort(
      (a, b) =>
        Number(b.rating || 0) -
        Number(a.rating || 0)
    )
  }

  return result
})

onMounted(async () => {
  await loadCities()

  if (cities.value.length > 0) {
    selectedCity.value = cities.value[0].id

    await loadPlaces()
  }

  await loadSavedPlans()
})

const loadCities = async () => {
  const res = await fetch(
    'http://127.0.0.1:8000/api/cities'
  )

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
  return places.value.filter(
    (place) => place.type === type
  ).length
}

const getPlaceCoordinates = (place) => {
  return (
    placeCoordinates[place.name] ||
    cityCoordinates[place.city_id] ||
    [50, 10]
  )
}

const focusMapOnPlace = (place) => {
  const coordinates = getPlaceCoordinates(place)

  if (mapRef.value?.leafletObject) {
    mapRef.value.leafletObject.setView(
      coordinates,
      14
    )
  }
}

const focusPlan = () => {
  if (plan.value.length === 0) return

  const coordinates = plan.value.map((place) =>
    getPlaceCoordinates(place)
  )

  if (
    mapRef.value?.leafletObject &&
    coordinates.length >= 2
  ) {
    mapRef.value.leafletObject.fitBounds(
      coordinates
    )

    return
  }

  focusMapOnPlace(plan.value[0])
}

const addToPlan = (place) => {
  if (
    !plan.value.find(
      (item) => item.id === place.id
    )
  ) {
    plan.value.push(place)

    focusMapOnPlace(place)

    saveMessage.value =
      'Vieta pievienota plānam'
  }
}

const removeFromPlan = (placeId) => {
  plan.value = plan.value.filter(
    (item) => item.id !== placeId
  )

  saveMessage.value =
    'Vieta noņemta no plāna'
}

const resetCurrentPlan = () => {
  plan.value = []
  planTitle.value = 'Mans ceļojuma plāns'
  editingPlanId.value = null
  saveMessage.value = 'Izveidots jauns tukšs plāns'
}

const savePlan = async () => {
  const token = localStorage.getItem('token')

  if (!token) {
    saveMessage.value =
      'Lūdzu, pieslēdzies pirms plāna saglabāšanas'

    return
  }

  const res = await fetch(
    'http://127.0.0.1:8000/api/plans',
    {
      method: 'POST',

      headers: {
        'Content-Type': 'application/json',
        Authorization: 'Bearer ' + token
      },

      body: JSON.stringify({
        title:
          planTitle.value ||
          'Mans ceļojuma plāns',

        places: plan.value
      })
    }
  )

  const data = await res.json()

  if (!res.ok) {
    saveMessage.value =
      data.message ||
      'Kļūda saglabājot plānu'

    return
  }

  saveMessage.value =
    'Ceļojuma plāns saglabāts'

  await loadSavedPlans()
}

const updatePlan = async () => {
  const token = localStorage.getItem('token')

  if (!token) {
    saveMessage.value =
      'Lūdzu, pieslēdzies pirms plāna atjaunināšanas'

    return
  }

  if (!editingPlanId.value) {
    saveMessage.value =
      'Nav izvēlēts saglabāts plāns rediģēšanai'

    return
  }

  const res = await fetch(
    `http://127.0.0.1:8000/api/plans/${editingPlanId.value}`,
    {
      method: 'PUT',

      headers: {
        'Content-Type': 'application/json',
        Authorization: 'Bearer ' + token
      },

      body: JSON.stringify({
        title:
          planTitle.value ||
          'Mans ceļojuma plāns',

        places: plan.value
      })
    }
  )

  const data = await res.json()

  if (!res.ok) {
    saveMessage.value =
      data.message ||
      'Kļūda atjauninot plānu'

    return
  }

  saveMessage.value =
    'Ceļojuma plāns atjaunināts'

  await loadSavedPlans()
}

const loadSavedPlans = async () => {
  const token = localStorage.getItem('token')

  if (!token) {
    savedPlans.value = []

    return
  }

  const res = await fetch(
    'http://127.0.0.1:8000/api/my-plans',
    {
      headers: {
        Authorization: 'Bearer ' + token
      }
    }
  )

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
    editingPlanId.value = savedPlan.id

    saveMessage.value = 'Plāns ielādēts rediģēšanai'

    if (plan.value.length > 0) {
      focusPlan()
    }
  } catch (error) {
    saveMessage.value =
      'Neizdevās ielādēt plānu'
  }
}

const deletePlan = async (planId) => {
  const token = localStorage.getItem('token')

  if (!token) {
    saveMessage.value =
      'Lūdzu, pieslēdzies pirms plāna dzēšanas'

    return
  }

  const res = await fetch(
    `http://127.0.0.1:8000/api/plans/${planId}`,
    {
      method: 'DELETE',

      headers: {
        Authorization: 'Bearer ' + token
      }
    }
  )

  const data = await res.json()

  if (!res.ok) {
    saveMessage.value =
      data.message ||
      'Kļūda dzēšot plānu'

    return
  }

  if (editingPlanId.value === planId) {
    resetCurrentPlan()
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
  padding: 48px;
  font-family: Inter, Arial, sans-serif;
}

.hero {
  max-width: 1000px;
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
  font-size: 18px;
  max-width: 760px;
  margin-top: 18px;
}

.layout {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 24px;
}

.panel {
  border: 1px solid var(--border-color);
  border-radius: 24px;
  padding: 28px;
  backdrop-filter: blur(18px);
  background: var(--card-bg);
}

.label {
  display: block;
  margin-bottom: 10px;
  color: #8ea0ff;
  font-size: 14px;
}

.select,
.input {
  width: 100%;
  padding: 14px 16px;
  border-radius: 14px;
  border: 1px solid var(--border-color);
  font-size: 16px;
  background: var(--input-bg);
  color: var(--text-main);
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
  border: 1px solid var(--border-color);
  border-radius: 16px;
  padding: 16px;
  background: var(--card-bg);
}

.stat-card strong {
  display: block;
  font-size: 28px;
  margin-bottom: 4px;
}

.stat-card span {
  color: #8ea0ff;
  font-size: 13px;
}

h2 {
  margin: 0 0 18px;
  font-size: 28px;
}

.cards {
  display: grid;
  gap: 20px;
}

.card {
  border: 1px solid var(--border-color);
  overflow: hidden;
  border-radius: 22px;
  background: var(--card-bg);
}

.place-card {
  display: grid;
  grid-template-columns: 260px 1fr;
  align-items: stretch;
}

.place-image {
  width: 100%;
  height: 100%;
  min-height: 220px;
  object-fit: cover;
  background: #1a1a1a;
}

.place-info {
  padding: 22px;
}

.card-top {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
}

.card h3 {
  margin: 14px 0 10px;
  font-size: 24px;
}

.description {
  line-height: 1.6;
  margin-bottom: 18px;
  color: var(--text-muted);
}

.badge {
  display: inline-block;
  padding: 7px 11px;
  border-radius: 999px;
  background: rgba(142, 160, 255, 0.18);
  color: #8ea0ff;
  font-size: 12px;
  text-transform: uppercase;
  font-weight: 800;
}

.rating {
  font-weight: 800;
  color: #ffc857;
}

.btn,
.save-btn,
.small-btn,
.remove-btn,
.popup-btn,
.secondary-btn {
  border: none;
  border-radius: 14px;
  padding: 12px 18px;
  background: linear-gradient(
    135deg,
    #6a5aff,
    #8b7dff
  );
  color: white;
  cursor: pointer;
  font-weight: 800;
}

.btn:hover,
.save-btn:hover,
.small-btn:hover,
.remove-btn:hover,
.secondary-btn:hover {
  opacity: 0.9;
  transform: translateY(-1px);
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
  background: linear-gradient(
    135deg,
    #ff4d6d,
    #ff758f
  );
}

.secondary-btn {
  width: 100%;
  margin-top: 10px;
  background: rgba(255, 255, 255, 0.12);
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
  height: 420px;
  border: 1px solid rgba(255, 255, 255, 0.14);
  box-shadow: var(--shadow);
}

.map {
  height: 420px;
  width: 100%;
}

.popup-btn {
  margin-top: 8px;
  padding: 7px 10px;
  font-size: 12px;
}

.plan-panel {
  position: sticky;
  top: 108px;
  height: fit-content;
}

.plan-title-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 14px;
}

.editing-badge {
  padding: 7px 11px;
  border-radius: 999px;
  background: rgba(32, 201, 151, 0.16);
  color: #20c997;
  font-size: 12px;
  font-weight: 900;
  text-transform: uppercase;
}

.edit-note {
  color: var(--text-muted);
  line-height: 1.5;
  margin: 0 0 16px;
  font-size: 14px;
}

.plan-actions {
  margin-top: 10px;
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
  border-bottom: 1px solid var(--border-color);
}

.plan-list small {
  display: block;
  margin-top: 4px;
  color: var(--text-muted);
}

.empty {
  padding: 18px 0;
  color: var(--text-muted);
}

.save-btn {
  width: 100%;
}

.save-btn:disabled {
  opacity: 0.45;
  cursor: not-allowed;
}

.success {
  color: #20c997;
  margin-top: 14px;
  font-weight: 800;
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
  border: 1px solid var(--border-color);
  border-radius: 16px;
  padding: 16px;
  background: var(--card-bg);
}

.saved-card.active {
  border-color: #20c997;
  box-shadow: 0 0 0 2px rgba(32, 201, 151, 0.18);
}

.saved-card h3 {
  margin: 0 0 6px;
  font-size: 16px;
}

.saved-actions {
  display: flex;
  gap: 8px;
  align-items: center;
}

@media (max-width: 1200px) {
  .tools {
    grid-template-columns: 1fr;
  }

  .stats {
    grid-template-columns: repeat(2, 1fr);
  }

  .place-card {
    grid-template-columns: 1fr;
  }

  .place-image {
    height: 260px;
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

  .plan-panel {
    position: static;
  }

  .saved-card {
    align-items: flex-start;
    flex-direction: column;
  }

  .map-wrapper,
  .map {
    height: 340px;
  }
}

@media (max-width: 600px) {
  .page {
    padding: 16px;
  }

  h1 {
    font-size: 32px;
  }

  .subtitle {
    font-size: 16px;
  }

  .panel {
    padding: 18px;
    border-radius: 20px;
  }

  .stats {
    grid-template-columns: 1fr;
  }

  .card-top {
    align-items: flex-start;
    flex-direction: column;
  }

  .place-info {
    padding: 18px;
  }

  .place-image {
    height: 210px;
  }

  .saved-actions {
    width: 100%;
  }

  .saved-actions button {
    flex: 1;
  }

  .map-title {
    align-items: flex-start;
    flex-direction: column;
    gap: 12px;
  }
}
</style>