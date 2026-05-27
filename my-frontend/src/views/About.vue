<template>
  <Navbar />

  <div class="page">
    <section class="hero">
      <p class="eyebrow">Par projektu</p>

      <h1>Ceļojumu plānotājs</h1>

      <p class="subtitle">
        Tīmekļa lietotne ceļojumu plānošanai, kurā lietotājs var izvēlēties pilsētu,
        apskatīt vietas, izmantot karti un saglabāt savu ceļojuma plānu.
      </p>
    </section>

    <section class="summary-grid">
      <div class="summary-card">
        <span class="summary-icon">🌍</span>
        <strong>6</strong>
        <p>Pilsētas</p>
      </div>

      <div class="summary-card">
        <span class="summary-icon">📍</span>
        <strong>54</strong>
        <p>Ceļojuma vietas</p>
      </div>

      <div class="summary-card">
        <span class="summary-icon">🗺️</span>
        <strong>Leaflet</strong>
        <p>Interaktīva karte</p>
      </div>

      <div class="summary-card">
        <span class="summary-icon">🔐</span>
        <strong>Sanctum</strong>
        <p>Autorizācija</p>
      </div>
    </section>

    <section class="grid">
      <article class="card large-card">
        <div class="card-header">
          <span class="icon">🎯</span>
          <h2>Projekta mērķis</h2>
        </div>

        <p>
          Projekta mērķis ir izveidot lietotājam draudzīgu ceļojumu plānošanas sistēmu,
          kurā iespējams izvēlēties pilsētu, apskatīt viesnīcas, restorānus un muzejus,
          pievienot izvēlētās vietas savam plānam un saglabāt šo plānu savā kontā.
        </p>
      </article>

      <article class="card">
        <div class="card-header">
          <span class="icon">⚙️</span>
          <h2>Galvenās funkcijas</h2>
        </div>

        <ul>
          <li>Reģistrācija un pieslēgšanās sistēmā</li>
          <li>Pilsētu un vietu apskate</li>
          <li>Meklēšana pēc vietas nosaukuma</li>
          <li>Filtrēšana pēc vietas tipa</li>
          <li>Kārtošana pēc nosaukuma vai reitinga</li>
          <li>Vietu pievienošana ceļojuma plānam</li>
          <li>Saglabāto plānu apskate un dzēšana</li>
        </ul>
      </article>

      <article class="card">
        <div class="card-header">
          <span class="icon">🧩</span>
          <h2>Tehnoloģijas</h2>
        </div>

        <ul>
          <li>Vue.js frontend daļai</li>
          <li>Vue Router lapu navigācijai</li>
          <li>Laravel backend API daļai</li>
          <li>SQLite datu bāzei</li>
          <li>Laravel Sanctum autorizācijai</li>
          <li>Leaflet interaktīvai kartei</li>
        </ul>
      </article>
    </section>

    <section class="details-grid">
      <article class="panel info-panel">
        <div class="card-header">
          <span class="icon">🗂️</span>
          <h2>Projekta struktūra</h2>
        </div>

        <div class="structure-list">
          <div class="structure-item">
            <strong>Frontend</strong>
            <span>Vue.js lietotāja saskarne ar lapām Home, SavedPlans, About, Login un Register.</span>
          </div>

          <div class="structure-item">
            <strong>Backend</strong>
            <span>Laravel API nodrošina pilsētu, vietu, lietotāju un ceļojumu plānu apstrādi.</span>
          </div>

          <div class="structure-item">
            <strong>Datu bāze</strong>
            <span>SQLite saglabā lietotājus, pilsētas, vietas un saglabātos ceļojuma plānus.</span>
          </div>

          <div class="structure-item">
            <strong>Autorizācija</strong>
            <span>Laravel Sanctum tiek izmantots lietotāju reģistrācijai, pieslēgšanai un drošai API piekļuvei.</span>
          </div>
        </div>
      </article>

      <article class="panel info-panel">
        <div class="card-header">
          <span class="icon">🚀</span>
          <h2>Lietotāja iespējas</h2>
        </div>

        <div class="feature-list">
          <div class="feature-item">
            <span>01</span>
            <p>Izvēlēties pilsētu no pieejamā saraksta.</p>
          </div>

          <div class="feature-item">
            <span>02</span>
            <p>Apskatīt viesnīcas, restorānus un muzejus ar aprakstiem, attēliem un reitingiem.</p>
          </div>

          <div class="feature-item">
            <span>03</span>
            <p>Pievienot interesējošās vietas savam ceļojuma plānam.</p>
          </div>

          <div class="feature-item">
            <span>04</span>
            <p>Redzēt izvēlētās vietas kartē un maršruta līniju starp tām.</p>
          </div>

          <div class="feature-item">
            <span>05</span>
            <p>Saglabāt plānu savā kontā un apskatīt to vēlāk.</p>
          </div>
        </div>
      </article>
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
  font-size: 18px;
  max-width: 820px;
  margin-top: 18px;
  line-height: 1.6;
}

.summary-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 18px;
  margin-bottom: 24px;
}

.summary-card {
  border: 1px solid var(--border-color);
  border-radius: 22px;
  padding: 22px;
  background: var(--card-bg);
  box-shadow: var(--shadow);
}

.summary-icon {
  display: block;
  font-size: 24px;
  margin-bottom: 14px;
}

.summary-card strong {
  display: block;
  font-size: 28px;
  margin-bottom: 6px;
}

.summary-card p {
  margin: 0;
  color: var(--text-muted);
  font-weight: 700;
}

.grid {
  display: grid;
  grid-template-columns: 1.1fr 1fr 1fr;
  gap: 22px;
  margin-bottom: 24px;
}

.card,
.panel {
  border: 1px solid var(--border-color);
  border-radius: 24px;
  padding: 26px;
  background: var(--card-bg);
  box-shadow: var(--shadow);
}

.card-header {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 18px;
}

.icon {
  width: 42px;
  height: 42px;
  border-radius: 14px;
  display: grid;
  place-items: center;
  background: rgba(142, 160, 255, 0.18);
  font-size: 20px;
}

.card h2,
.panel h2 {
  margin: 0;
  font-size: 24px;
}

.card p,
.card li,
.panel p,
.structure-item span {
  color: var(--text-muted);
  line-height: 1.65;
}

.card ul {
  margin: 0;
  padding-left: 22px;
}

.card li {
  margin-bottom: 8px;
}

.details-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 22px;
}

.structure-list {
  display: grid;
  gap: 14px;
}

.structure-item {
  padding: 16px;
  border: 1px solid var(--border-color);
  border-radius: 18px;
  background: var(--card-bg-strong);
}

.structure-item strong {
  display: block;
  margin-bottom: 8px;
  color: #8ea0ff;
}

.feature-list {
  display: grid;
  gap: 14px;
}

.feature-item {
  display: grid;
  grid-template-columns: 46px 1fr;
  gap: 14px;
  align-items: start;
  padding: 16px;
  border: 1px solid var(--border-color);
  border-radius: 18px;
  background: var(--card-bg-strong);
}

.feature-item span {
  width: 38px;
  height: 38px;
  border-radius: 13px;
  display: grid;
  place-items: center;
  background: linear-gradient(135deg, #6a5aff, #8b7dff);
  color: white;
  font-weight: 900;
  font-size: 13px;
}

.feature-item p {
  margin: 0;
  color: var(--text-muted);
  line-height: 1.55;
}

@media (max-width: 1150px) {
  .summary-grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .grid {
    grid-template-columns: 1fr;
  }

  .details-grid {
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

  .summary-grid {
    grid-template-columns: 1fr;
  }

  .card,
  .panel,
  .summary-card {
    padding: 20px;
    border-radius: 20px;
  }
}

@media (max-width: 500px) {
  .page {
    padding: 16px;
  }

  h1 {
    font-size: 32px;
  }

  .feature-item {
    grid-template-columns: 1fr;
  }
}
</style>