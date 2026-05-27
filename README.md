# Travel App — Ceļojumu plānotājs

Travel App ir ceļojumu plānošanas tīmekļa lietotne, kurā lietotājs var izvēlēties pilsētu, apskatīt viesnīcas, restorānus un muzejus, pievienot vietas savam ceļojuma plānam un saglabāt plānu savā kontā.

Projekts veidots ar Vue.js frontend daļā un Laravel backend API daļā.

## Galvenās funkcijas

- Lietotāja reģistrācija un pieslēgšanās
- Ceļojuma pilsētas izvēle
- Vietu apskate pēc pilsētas
- Vietu meklēšana pēc nosaukuma
- Filtrēšana pēc tipa: viesnīcas, restorāni, muzeji
- Kārtošana pēc nosaukuma vai reitinga
- Vietu pievienošana ceļojuma plānam
- Interaktīva karte ar Leaflet
- Maršruta līnija starp izvēlētajām vietām
- Ceļojuma plāna saglabāšana
- Saglabāto plānu apskate un dzēšana
- Tumšais un gaišais režīms
- Responsīvs dizains mobilajām ierīcēm

## Tehnoloģijas

### Frontend

- Vue 3
- Vite
- Vue Router
- Leaflet
- Vue Leaflet
- CSS

### Backend

- Laravel
- Laravel Sanctum
- SQLite
- REST API

## Projekta struktūra

```text
travel-planner/
│
├── my-frontend/
│   ├── src/
│   │   ├── components/
│   │   │   └── Navbar.vue
│   │   ├── views/
│   │   │   ├── Home.vue
│   │   │   ├── SavedPlans.vue
│   │   │   ├── About.vue
│   │   │   ├── Login.vue
│   │   │   └── Register.vue
│   │   ├── router/
│   │   │   └── index.js
│   │   └── App.vue
│   └── package.json
│
└── my-laravel-app/
    ├── routes/
    │   └── api.php
    ├── database/
    │   └── migrations/
    └── .env
```

## Projekta palaišana lokāli

Lai projekts darbotos, jāpalaiž gan Laravel backend, gan Vue frontend.

## Backend palaišana

Atver termināli backend mapē:

```bash
cd my-laravel-app
```

Instalē PHP dependencies:

```bash
composer install
```

Izveido `.env` failu, ja tas vēl nav izveidots:

```bash
copy .env.example .env
```

Ģenerē Laravel aplikācijas atslēgu:

```bash
php artisan key:generate
```

Palaid migrācijas:

```bash
php artisan migrate
```

Palaid Laravel serveri:

```bash
php artisan serve
```

Backend darbosies adresē:

```text
http://127.0.0.1:8000
```

Backend pārbaude:

```text
http://127.0.0.1:8000/api/test
```

Aizpildi datu bāzi ar pilsētām un vietām:

```text
http://127.0.0.1:8000/api/seed-cities
http://127.0.0.1:8000/api/seed-places
```

## Frontend palaišana

Atver otru termināli frontend mapē:

```bash
cd my-frontend
```

Instalē dependencies:

```bash
npm install
```

Palaid Vue projektu:

```bash
npm run dev
```

Frontend darbosies adresē:

```text
http://localhost:5173
```

## API maršruti

### Auth

```text
POST /api/register
POST /api/login
POST /api/logout
```

### Cities

```text
GET /api/cities
GET /api/seed-cities
```

### Places

```text
GET /api/places/{city}
GET /api/seed-places
```

### Plans

```text
POST /api/plans
GET /api/my-plans
DELETE /api/plans/{id}
```

## Lietošana

1. Lietotājs izveido kontu vai pieslēdzas sistēmai.
2. Sākuma lapā izvēlas pilsētu.
3. Apskata vietas, izmanto meklēšanu, filtrēšanu un kārtošanu.
4. Pievieno interesējošās vietas ceļojuma plānam.
5. Saglabā plānu.
6. Saglabāto plānu lapā var apskatīt un dzēst savus plānus.

## Autors

Projekts izveidots kā ceļojumu plānošanas tīmekļa lietotne, izmantojot Vue.js un Laravel.