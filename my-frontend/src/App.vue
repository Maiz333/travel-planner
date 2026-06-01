<template>
  <router-view />
</template>

<style>
* {
  box-sizing: border-box;
}

html,
body,
#app {
  min-height: 100%;
  margin: 0;
}

body {
  font-family: Inter, Arial, sans-serif;
  background: var(--page-bg);
  color: var(--text-main);
  transition: background 0.25s ease, color 0.25s ease;
}

body.dark {
  --page-bg: #0f1020;
  --page-gradient:
    radial-gradient(circle at top left, rgba(106, 90, 255, 0.24), transparent 32%),
    linear-gradient(135deg, #0f1020, #171927);

  --nav-bg: rgba(20, 22, 42, 0.94);
  --card-bg: rgba(255, 255, 255, 0.08);
  --card-bg-strong: rgba(15, 16, 32, 0.82);
  --input-bg: #101223;

  --text-main: #ffffff;
  --text-muted: #b7bdd8;
  --text-soft: #9ba5d9;

  --border-color: rgba(255, 255, 255, 0.12);
  --shadow: 0 20px 50px rgba(0, 0, 0, 0.35);
}

body.light {
  --page-bg: #f4f6fb;
  --page-gradient:
    radial-gradient(circle at top left, rgba(106, 90, 255, 0.12), transparent 34%),
    linear-gradient(135deg, #f7f8ff, #eef1fb);

  --nav-bg: rgba(255, 255, 255, 0.96);
  --card-bg: rgba(255, 255, 255, 0.88);
  --card-bg-strong: #ffffff;
  --input-bg: #ffffff;

  --text-main: #15172a;
  --text-muted: #5d6380;
  --text-soft: #707790;

  --border-color: rgba(20, 22, 42, 0.12);
  --shadow: 0 20px 50px rgba(30, 35, 70, 0.14);
}

body:not(.dark):not(.light) {
  --page-bg: #0f1020;
  --page-gradient:
    radial-gradient(circle at top left, rgba(106, 90, 255, 0.24), transparent 32%),
    linear-gradient(135deg, #0f1020, #171927);

  --nav-bg: rgba(20, 22, 42, 0.94);
  --card-bg: rgba(255, 255, 255, 0.08);
  --card-bg-strong: rgba(15, 16, 32, 0.82);
  --input-bg: #101223;

  --text-main: #ffffff;
  --text-muted: #b7bdd8;
  --text-soft: #9ba5d9;

  --border-color: rgba(255, 255, 255, 0.12);
  --shadow: 0 20px 50px rgba(0, 0, 0, 0.35);
}

.page,
.auth-page {
  background: var(--page-gradient) !important;
  color: var(--text-main) !important;
  animation: pageFade 0.22s ease;
}

.panel,
.auth-card,
.card,
.saved-card,
.stat-card,
.mini-stat,
.place-card,
.info-card {
  background: var(--card-bg) !important;
  border-color: var(--border-color) !important;
  color: var(--text-main) !important;
  box-shadow: var(--shadow) !important;
}

.input,
.select,
.auth-input {
  background: var(--input-bg) !important;
  color: var(--text-main) !important;
  border-color: var(--border-color) !important;
}

.subtitle,
.empty,
.saved-card small,
.plan-list small,
.card p,
.card li,
.place-card p,
.section-text,
.info-card p,
.info-card li {
  color: var(--text-muted) !important;
}

a,
button,
input,
select {
  transition:
    background 0.2s ease,
    color 0.2s ease,
    opacity 0.2s ease,
    border-color 0.2s ease,
    box-shadow 0.2s ease;
}

button:hover {
  opacity: 0.92;
}

@keyframes pageFade {
  from {
    opacity: 0;
    transform: translateY(8px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>