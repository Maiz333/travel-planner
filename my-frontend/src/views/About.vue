<template>
  <Navbar />

  <div class="page">
    <section class="hero">
      <div>
        <p class="eyebrow">Par projektu</p>

        <h1>Ceļojumu plānotājs</h1>

        <p class="subtitle">
          Tīmekļa lietotne ceļojumu plānošanai ar pilsētām, vietām,
          interaktīvu karti, saglabātiem plāniem un administratora paneli.
        </p>

        <div class="hero-actions">
          <router-link to="/home" class="primary-link">
            Sākt plānot
          </router-link>

          <router-link to="/saved-plans" class="secondary-link">
            Skatīt plānus
          </router-link>
        </div>
      </div>

      <div class="hero-card">
        <div class="hero-icon">✈️</div>

        <h2>Travel App</h2>

        <p>
          Izvēlies pilsētu, atrodi interesantas vietas un izveido savu
          ceļojuma maršrutu vienā vietā.
        </p>
      </div>
    </section>

    <section class="features">
      <div class="feature-card">
        <span class="feature-icon">🌍</span>
        <h3>Pilsētas</h3>
        <p>Izvēlies kādu no populārām ceļojumu pilsētām.</p>
      </div>

      <div class="feature-card">
        <span class="feature-icon">🏨</span>
        <h3>Vietas</h3>
        <p>Apskati viesnīcas, restorānus un muzejus pēc pilsētas.</p>
      </div>

      <div class="feature-card">
        <span class="feature-icon">🗺️</span>
        <h3>Karte</h3>
        <p>Skaties vietas kartē un redzi maršruta līniju starp izvēlētajām vietām.</p>
      </div>

      <div class="feature-card">
        <span class="feature-icon">💾</span>
        <h3>Plāni</h3>
        <p>Saglabā, ielādē, rediģē un dzēs savus ceļojuma plānus.</p>
      </div>
    </section>

    <section class="grid">
      <div class="info-card">
        <h2>Projekta mērķis</h2>

        <p>
          Projekta mērķis ir izveidot lietotājam draudzīgu sistēmu,
          kurā var izvēlēties pilsētu, apskatīt viesnīcas, restorānus
          un muzejus, kā arī saglabāt savu ceļojuma plānu.
        </p>
      </div>

      <div class="info-card">
        <h2>Galvenās funkcijas</h2>

        <ul>
          <li>Reģistrācija un pieslēgšanās</li>
          <li>Pilsētu un vietu apskate</li>
          <li>Meklēšana, filtrēšana un kārtošana</li>
          <li>Interaktīva karte</li>
          <li>Ceļojuma plāna saglabāšana, rediģēšana un dzēšana</li>
          <li>Administratora panelis</li>
        </ul>
      </div>

      <div class="info-card">
        <h2>Tehnoloģijas</h2>

        <div class="tech-list">
          <span>Vue.js</span>
          <span>Vite</span>
          <span>Vue Router</span>
          <span>Laravel</span>
          <span>Laravel Sanctum</span>
          <span>SQLite</span>
          <span>Leaflet</span>
          <span>REST API</span>
        </div>
      </div>
    </section>

    <section class="workflow-card">
      <div>
        <p class="eyebrow">Lietošana</p>

        <h2>Kā darbojas lietotne?</h2>
      </div>

      <div class="steps">
        <div class="step">
          <strong>1</strong>
          <span>Izveido kontu vai pieslēdzies</span>
        </div>

        <div class="step">
          <strong>2</strong>
          <span>Izvēlies pilsētu un apskati vietas</span>
        </div>

        <div class="step">
          <strong>3</strong>
          <span>Pievieno vietas savam plānam</span>
        </div>

        <div class="step">
          <strong>4</strong>
          <span>Saglabā vai rediģē ceļojuma plānu</span>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import Navbar from '../components/Navbar.vue'
</script>

<style scoped>
.page {
  min-height: 100vh;
  padding: 48px;
  font-family: Inter, Arial, sans-serif;
}

.hero {
  display: grid;
  grid-template-columns: 1.4fr 0.8fr;
  gap: 26px;
  align-items: stretch;
  margin-bottom: 26px;
}

.eyebrow {
  color: #8ea0ff;
  text-transform: uppercase;
  letter-spacing: 3px;
  font-size: 13px;
  margin: 0 0 10px;
}

h1 {
  font-size: 58px;
  margin: 0;
  line-height: 1.05;
}

.subtitle {
  color: var(--text-muted);
  font-size: 18px;
  line-height: 1.6;
  max-width: 820px;
  margin-top: 18px;
}

.hero-actions {
  display: flex;
  gap: 14px;
  margin-top: 28px;
}

.primary-link,
.secondary-link {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-height: 48px;
  padding: 0 20px;
  border-radius: 15px;
  text-decoration: none;
  font-weight: 900;
}

.primary-link {
  color: white;
  background: linear-gradient(135deg, #6a5aff, #8b7dff);
}

.secondary-link {
  color: var(--text-main);
  background: var(--card-bg);
  border: 1px solid var(--border-color);
}

.hero-card,
.info-card,
.feature-card,
.workflow-card {
  background: var(--card-bg);
  border: 1px solid var(--border-color);
  border-radius: 24px;
  box-shadow: var(--shadow);
}

.hero-card {
  padding: 30px;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.hero-icon {
  width: 64px;
  height: 64px;
  display: grid;
  place-items: center;
  border-radius: 20px;
  background: rgba(142, 160, 255, 0.18);
  font-size: 30px;
  margin-bottom: 18px;
}

.hero-card h2 {
  margin: 0 0 12px;
  font-size: 30px;
}

.hero-card p {
  color: var(--text-muted);
  line-height: 1.6;
  margin: 0;
}

.features {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 18px;
  margin-bottom: 26px;
}

.feature-card {
  padding: 22px;
}

.feature-icon {
  display: block;
  font-size: 28px;
  margin-bottom: 14px;
}

.feature-card h3 {
  margin: 0 0 10px;
  font-size: 20px;
}

.feature-card p {
  color: var(--text-muted);
  line-height: 1.5;
  margin: 0;
}

.grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 22px;
  margin-bottom: 26px;
}

.info-card {
  padding: 26px;
}

.info-card h2 {
  margin-top: 0;
  margin-bottom: 16px;
  font-size: 24px;
}

.info-card p,
.info-card li {
  color: var(--text-muted);
  line-height: 1.6;
}

.info-card ul {
  margin: 0;
  padding-left: 20px;
}

.tech-list {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.tech-list span {
  padding: 9px 12px;
  border-radius: 999px;
  background: rgba(142, 160, 255, 0.18);
  color: #8ea0ff;
  font-weight: 900;
  font-size: 13px;
}

.workflow-card {
  padding: 28px;
}

.workflow-card h2 {
  margin: 0;
  font-size: 30px;
}

.steps {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 14px;
  margin-top: 22px;
}

.step {
  border: 1px solid var(--border-color);
  border-radius: 18px;
  padding: 18px;
  background: var(--card-bg-strong);
}

.step strong {
  width: 38px;
  height: 38px;
  display: grid;
  place-items: center;
  border-radius: 14px;
  background: linear-gradient(135deg, #6a5aff, #8b7dff);
  color: white;
  margin-bottom: 12px;
}

.step span {
  color: var(--text-muted);
  line-height: 1.5;
  font-weight: 700;
}

@media (max-width: 1100px) {
  .hero,
  .grid {
    grid-template-columns: 1fr;
  }

  .features,
  .steps {
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

  .subtitle {
    font-size: 16px;
  }

  .features,
  .steps {
    grid-template-columns: 1fr;
  }

  .hero-actions {
    flex-direction: column;
  }

  .primary-link,
  .secondary-link {
    width: 100%;
  }
}

@media (max-width: 500px) {
  .page {
    padding: 16px;
  }

  h1 {
    font-size: 32px;
  }

  .hero-card,
  .info-card,
  .feature-card,
  .workflow-card {
    padding: 20px;
    border-radius: 20px;
  }
}
</style>