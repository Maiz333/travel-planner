<template>
  <Navbar />

  <div class="page">
    <section class="hero">
      <p class="eyebrow">Ceļojumu plānotājs</p>

      <h1>Saglabātie plāni</h1>

      <p class="subtitle">
        Šeit vari apskatīt savus saglabātos ceļojuma plānus, redzēt pievienotās vietas,
        to tipus, reitingus un vajadzības gadījumā dzēst plānu.
      </p>
    </section>

    <section class="panel">
      <div class="saved-header">
        <div>
          <h2>Mani saglabātie plāni</h2>

          <p class="section-text">
            Kopā saglabāti plāni: {{ savedPlans.length }}
          </p>
        </div>

        <button class="small-btn" @click="loadSavedPlans">
          Atjaunot
        </button>
      </div>

      <div v-if="message" class="success">
        {{ message }}
      </div>

      <div v-if="savedPlans.length === 0" class="empty">
        Saglabātu plānu vēl nav.
      </div>

      <div class="saved-plans">
        <article
          v-for="savedPlan in savedPlans"
          :key="savedPlan.id"
          class="saved-card"
        >
          <div class="saved-card-top">
            <div>
              <h3>{{ savedPlan.title }}</h3>

              <small>
                {{ formatDate(savedPlan.created_at) }}
              </small>
            </div>

            <button
              class="danger-btn"
              @click="deletePlan(savedPlan.id)"
            >
              Dzēst
            </button>
          </div>

          <div class="plan-stats">
            <div class="mini-stat">
              <strong>{{ parsePlaces(savedPlan.places).length }}</strong>
              <span>Vietas</span>
            </div>

            <div class="mini-stat">
              <strong>{{ countPlanType(savedPlan.places, 'hotel') }}</strong>
              <span>Viesnīcas</span>
            </div>

            <div class="mini-stat">
              <strong>{{ countPlanType(savedPlan.places, 'restaurant') }}</strong>
              <span>Restorāni</span>
            </div>

            <div class="mini-stat">
              <strong>{{ countPlanType(savedPlan.places, 'museum') }}</strong>
              <span>Muzeji</span>
            </div>
          </div>

          <div class="places-grid">
            <div
              v-for="place in parsePlaces(savedPlan.places)"
              :key="place.id"
              class="place-card"
            >
              <img
                class="place-image"
                :src="getPlaceImage(place)"
                :alt="place.name"
                @error="setTypeFallback($event, place.type)"
              />

              <div class="place-info">
                <div class="place-top">
                  <span class="badge">
                    {{ translateType(place.type) }}
                  </span>

                  <span class="rating">
                    ⭐ {{ place.rating || '4.5' }}
                  </span>
                </div>

                <h4>{{ place.name }}</h4>

                <p>
                  {{
                    place.description ||
                    'Populāra vieta, kas pievienota ceļojuma plānam.'
                  }}
                </p>
              </div>
            </div>
          </div>
        </article>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import Navbar from '../components/Navbar.vue'

const savedPlans = ref([])
const message = ref('')

const fallbackImages = {
  hotel: 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1200&q=80',
  restaurant: 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1200&q=80',
  museum: 'https://images.unsplash.com/photo-1518998053901-5348d3961a04?auto=format&fit=crop&w=1200&q=80',
  default: 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=80'
}

onMounted(() => {
  loadSavedPlans()
})

const loadSavedPlans = async () => {
  const token = localStorage.getItem('token')

  if (!token) {
    savedPlans.value = []
    message.value = 'Lūdzu, pieslēdzies, lai redzētu savus plānus'
    return
  }

  const res = await fetch('http://127.0.0.1:8000/api/my-plans', {
    headers: {
      Authorization: 'Bearer ' + token
    }
  })

  if (!res.ok) {
    savedPlans.value = []
    message.value = 'Neizdevās ielādēt saglabātos plānus'
    return
  }

  savedPlans.value = await res.json()
  message.value = ''
}

const deletePlan = async (planId) => {
  const token = localStorage.getItem('token')

  if (!token) {
    message.value = 'Lūdzu, pieslēdzies'
    return
  }

  const confirmDelete = confirm('Vai tiešām vēlies dzēst šo plānu?')

  if (!confirmDelete) {
    return
  }

  const res = await fetch(`http://127.0.0.1:8000/api/plans/${planId}`, {
    method: 'DELETE',
    headers: {
      Authorization: 'Bearer ' + token
    }
  })

  if (!res.ok) {
    message.value = 'Kļūda dzēšot plānu'
    return
  }

  message.value = 'Plāns izdzēsts'
  await loadSavedPlans()
}

const parsePlaces = (places) => {
  try {
    if (Array.isArray(places)) {
      return places
    }

    return JSON.parse(places)
  } catch {
    return []
  }
}

const countPlanType = (places, type) => {
  return parsePlaces(places).filter((place) => place.type === type).length
}

const fixImage = (url) => {
  if (!url) return ''

  if (url.includes('images.unsplash.com') && !url.includes('?')) {
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

const translateType = (type) => {
  if (type === 'hotel') return 'Viesnīca'
  if (type === 'restaurant') return 'Restorāns'
  if (type === 'museum') return 'Muzejs'

  return type
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
  max-width: 780px;
  margin-top: 18px;
  color: var(--text-muted);
}

.panel {
  max-width: 1200px;
  border: 1px solid var(--border-color);
  border-radius: 24px;
  padding: 28px;
  backdrop-filter: blur(18px);
  background: var(--card-bg);
  box-shadow: var(--shadow);
}

.saved-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 18px;
  margin-bottom: 22px;
}

.saved-header h2 {
  margin: 0 0 8px;
  font-size: 28px;
}

.section-text {
  margin: 0;
  color: var(--text-muted);
}

.empty {
  padding: 24px 0;
  color: var(--text-muted);
}

.saved-plans {
  display: grid;
  gap: 22px;
}

.saved-card {
  border: 1px solid var(--border-color);
  border-radius: 22px;
  background: var(--card-bg-strong);
  padding: 22px;
  overflow: hidden;
}

.saved-card-top {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 18px;
  margin-bottom: 18px;
}

.saved-card h3 {
  margin: 0 0 6px;
  font-size: 24px;
}

.saved-card small {
  color: var(--text-muted);
}

.plan-stats {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 12px;
  margin-bottom: 20px;
}

.mini-stat {
  border: 1px solid var(--border-color);
  border-radius: 16px;
  padding: 14px;
  background: var(--card-bg);
}

.mini-stat strong {
  display: block;
  font-size: 24px;
  margin-bottom: 4px;
}

.mini-stat span {
  color: #8ea0ff;
  font-size: 13px;
  font-weight: 700;
}

.places-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 15px;
}

.place-card {
  border: 1px solid var(--border-color);
  border-radius: 18px;
  overflow: hidden;
  background: var(--card-bg);
}

.place-image {
  width: 100%;
  height: 150px;
  object-fit: cover;
  display: block;
  background: #1a1a1a;
}

.place-info {
  padding: 16px;
}

.place-top {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 12px;
  margin-bottom: 12px;
}

.badge {
  display: inline-block;
  padding: 6px 10px;
  border-radius: 999px;
  background: rgba(142, 160, 255, 0.18);
  color: #8ea0ff;
  font-size: 11px;
  text-transform: uppercase;
  font-weight: 800;
}

.rating {
  color: #ffc857;
  font-weight: 800;
  font-size: 13px;
}

.place-card h4 {
  margin: 0 0 10px;
  font-size: 17px;
}

.place-card p {
  margin: 0;
  line-height: 1.5;
  color: var(--text-muted);
  font-size: 14px;
}

.small-btn,
.danger-btn {
  border: none;
  border-radius: 14px;
  padding: 11px 16px;
  color: white;
  cursor: pointer;
  font-weight: 800;
}

.small-btn {
  background: linear-gradient(135deg, #6a5aff, #8b7dff);
}

.danger-btn {
  background: linear-gradient(135deg, #ff4d6d, #ff758f);
  height: fit-content;
}

.small-btn:hover,
.danger-btn:hover {
  opacity: 0.9;
  transform: translateY(-1px);
}

.success {
  color: #20c997;
  font-weight: 800;
  margin: 0 0 18px;
}

@media (max-width: 1000px) {
  .places-grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .plan-stats {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 700px) {
  .page {
    padding: 24px;
  }

  h1 {
    font-size: 38px;
  }

  .panel {
    padding: 20px;
  }

  .saved-header,
  .saved-card-top {
    flex-direction: column;
  }

  .small-btn,
  .danger-btn {
    width: 100%;
  }

  .places-grid {
    grid-template-columns: 1fr;
  }

  .plan-stats {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 500px) {
  .page {
    padding: 16px;
  }

  h1 {
    font-size: 32px;
  }

  .subtitle {
    font-size: 16px;
  }
}
</style>