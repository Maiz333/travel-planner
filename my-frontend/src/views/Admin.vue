<template>
  <Navbar />

  <div class="page">
    <section class="hero">
      <p class="eyebrow">Administrators</p>

      <h1>Vadības panelis</h1>

      <p class="subtitle">
        Šeit administrators var apskatīt sistēmas statistiku: lietotājus,
        pilsētas, vietas, plānus un datu sadalījumu.
      </p>
    </section>

    <section v-if="message" class="message-card">
      {{ message }}
    </section>

    <section v-if="isLoading" class="message-card">
      Dati tiek ielādēti...
    </section>

    <template v-if="!isLoading && !message">
      <section class="stats-grid">
        <div class="stat-card">
          <span class="stat-icon">👥</span>
          <strong>{{ stats.total_users }}</strong>
          <p>Lietotāji</p>
        </div>

        <div class="stat-card">
          <span class="stat-icon">🛡️</span>
          <strong>{{ stats.total_admins }}</strong>
          <p>Administratori</p>
        </div>

        <div class="stat-card">
          <span class="stat-icon">🌍</span>
          <strong>{{ stats.total_cities }}</strong>
          <p>Pilsētas</p>
        </div>

        <div class="stat-card">
          <span class="stat-icon">📍</span>
          <strong>{{ stats.total_places }}</strong>
          <p>Vietas</p>
        </div>

        <div class="stat-card">
          <span class="stat-icon">🧳</span>
          <strong>{{ stats.total_plans }}</strong>
          <p>Plāni</p>
        </div>
      </section>

      <section class="dashboard-grid">
        <article class="panel">
          <div class="panel-header">
            <h2>Vietas pēc tipa</h2>
            <span>Grupēšana</span>
          </div>

          <div class="rows">
            <div
              v-for="item in stats.places_by_type"
              :key="item.type"
              class="row"
            >
              <div>
                <strong>{{ translateType(item.type) }}</strong>
                <small>{{ item.type }}</small>
              </div>

              <span class="count">{{ item.total }}</span>
            </div>
          </div>
        </article>

        <article class="panel">
          <div class="panel-header">
            <h2>Vietas pēc pilsētas</h2>
            <span>Saistītas tabulas</span>
          </div>

          <div class="rows">
            <div
              v-for="item in stats.places_by_city"
              :key="item.city"
              class="row"
            >
              <div>
                <strong>{{ item.city }}</strong>
                <small>Vietas pilsētā</small>
              </div>

              <span class="count">{{ item.total }}</span>
            </div>
          </div>
        </article>
      </section>

      <section class="panel users-panel">
        <div class="panel-header">
          <h2>Lietotāji un plāni</h2>
          <span>Atlase no vairākām tabulām</span>
        </div>

        <div class="table-wrapper">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Vārds</th>
                <th>E-pasts</th>
                <th>Loma</th>
                <th>Plānu skaits</th>
              </tr>
            </thead>

            <tbody>
              <tr
                v-for="user in stats.plans_by_user"
                :key="user.id"
              >
                <td>{{ user.id }}</td>
                <td>{{ user.name }}</td>
                <td>{{ user.email }}</td>
                <td>
                  <span
                    class="role-badge"
                    :class="{ admin: user.role === 'admin' }"
                  >
                    {{ user.role }}
                  </span>
                </td>
                <td>{{ user.plans_count }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
    </template>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import Navbar from '../components/Navbar.vue'

const router = useRouter()

const API_URL = import.meta.env.VITE_API_URL || 'http://127.0.0.1:8000/api'

const isLoading = ref(true)
const message = ref('')

const stats = ref({
  total_users: 0,
  total_admins: 0,
  total_cities: 0,
  total_places: 0,
  total_plans: 0,
  places_by_type: [],
  places_by_city: [],
  plans_by_user: []
})

onMounted(() => {
  loadAdminStats()
})

const loadAdminStats = async () => {
  const token = localStorage.getItem('token')

  if (!token) {
    router.push('/login')
    return
  }

  try {
    const res = await fetch(`${API_URL}/admin/stats`, {
      headers: {
        Authorization: 'Bearer ' + token
      }
    })

    const data = await res.json()

    if (res.status === 403) {
      message.value = 'Tev nav administratora tiesību'
      return
    }

    if (!res.ok) {
      message.value = data.message || 'Neizdevās ielādēt admin datus'
      return
    }

    stats.value = data
  } catch (error) {
    message.value = 'Backend nav pieejams'
  } finally {
    isLoading.value = false
  }
}

const translateType = (type) => {
  if (type === 'hotel') return 'Viesnīcas'
  if (type === 'restaurant') return 'Restorāni'
  if (type === 'museum') return 'Muzeji'

  return type
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
  margin-bottom: 34px;
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
  max-width: 820px;
  margin-top: 18px;
  font-size: 18px;
  line-height: 1.6;
  color: var(--text-muted);
}

.message-card {
  max-width: 900px;
  border: 1px solid var(--border-color);
  border-radius: 22px;
  padding: 22px;
  background: var(--card-bg);
  box-shadow: var(--shadow);
  color: var(--text-muted);
  font-weight: 800;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 18px;
  margin-bottom: 24px;
}

.stat-card {
  border: 1px solid var(--border-color);
  border-radius: 22px;
  padding: 22px;
  background: var(--card-bg);
  box-shadow: var(--shadow);
}

.stat-icon {
  display: block;
  font-size: 26px;
  margin-bottom: 14px;
}

.stat-card strong {
  display: block;
  font-size: 34px;
  margin-bottom: 6px;
}

.stat-card p {
  margin: 0;
  color: var(--text-muted);
  font-weight: 800;
}

.dashboard-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 22px;
  margin-bottom: 24px;
}

.panel {
  border: 1px solid var(--border-color);
  border-radius: 24px;
  padding: 26px;
  background: var(--card-bg);
  box-shadow: var(--shadow);
}

.panel-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 18px;
  margin-bottom: 20px;
}

.panel-header h2 {
  margin: 0;
  font-size: 26px;
}

.panel-header span {
  padding: 7px 11px;
  border-radius: 999px;
  background: rgba(142, 160, 255, 0.18);
  color: #8ea0ff;
  font-size: 12px;
  font-weight: 900;
  text-transform: uppercase;
}

.rows {
  display: grid;
  gap: 12px;
}

.row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 14px;
  padding: 16px;
  border: 1px solid var(--border-color);
  border-radius: 18px;
  background: var(--card-bg-strong);
}

.row strong {
  display: block;
  margin-bottom: 4px;
}

.row small {
  color: var(--text-muted);
}

.count {
  min-width: 46px;
  height: 42px;
  border-radius: 14px;
  display: grid;
  place-items: center;
  background: linear-gradient(135deg, #6a5aff, #8b7dff);
  color: white;
  font-weight: 900;
}

.users-panel {
  max-width: 1200px;
}

.table-wrapper {
  width: 100%;
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
  min-width: 720px;
}

th,
td {
  padding: 15px;
  border-bottom: 1px solid var(--border-color);
  text-align: left;
}

th {
  color: #8ea0ff;
  font-size: 13px;
  text-transform: uppercase;
  letter-spacing: 1px;
}

td {
  color: var(--text-main);
}

.role-badge {
  display: inline-block;
  padding: 6px 10px;
  border-radius: 999px;
  background: rgba(142, 160, 255, 0.18);
  color: #8ea0ff;
  font-size: 12px;
  font-weight: 900;
  text-transform: uppercase;
}

.role-badge.admin {
  background: rgba(32, 201, 151, 0.16);
  color: #20c997;
}

@media (max-width: 1200px) {
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .dashboard-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 700px) {
  .page {
    padding: 24px;
  }

  h1 {
    font-size: 38px;
  }

  .subtitle {
    font-size: 16px;
  }

  .stats-grid {
    grid-template-columns: 1fr;
  }

  .panel,
  .stat-card {
    padding: 20px;
    border-radius: 20px;
  }

  .panel-header {
    flex-direction: column;
  }
}

@media (max-width: 500px) {
  .page {
    padding: 16px;
  }

  h1 {
    font-size: 32px;
  }
}
</style>