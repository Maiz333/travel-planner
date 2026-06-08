# Travel App — Ceļojumu plānotājs

Travel App ir ceļojumu plānošanas tīmekļa lietotne, kurā lietotājs var izvēlēties pilsētu, apskatīt viesnīcas, restorānus un muzejus, pievienot vietas savam ceļojuma plānam un saglabāt plānu savā kontā.

Projekts ir izstrādāts kā noslēguma darbs, izmantojot Vue.js frontend daļā, Laravel backend API daļā un datu bāzi datu glabāšanai.

---

## Publiskās saites

Frontend daļa ir izvietota Vercel platformā:

```text
https://travel-planner-beta-six.vercel.app
```

Backend daļa ir izvietota Railway platformā:

```text
https://travel-planner-production-9ceb.up.railway.app
```

Backend pārbaudes API:

```text
https://travel-planner-production-9ceb.up.railway.app/api/test
```

---

## Projekta mērķis

Projekta mērķis ir izveidot lietotājam draudzīgu ceļojumu plānošanas sistēmu, kurā lietotāji var apskatīt dažādas pilsētas, izvēlēties interesantas vietas, izveidot savu ceļojuma plānu un saglabāt to savā profilā.

Sistēmā ir arī administratora vadības panelis, kur administrators var apskatīt sistēmas statistiku par lietotājiem, pilsētām, vietām un saglabātajiem plāniem.

---

## Galvenās funkcijas

* Lietotāja reģistrācija un pieslēgšanās
* Lietotāju autentifikācija ar Laravel Sanctum
* Lietotāju lomu sadalījums: lietotājs un administrators
* Ceļojuma pilsētas izvēle
* Vietu apskate pēc pilsētas
* Vietu meklēšana pēc nosaukuma
* Filtrēšana pēc tipa: viesnīcas, restorāni, muzeji
* Kārtošana pēc nosaukuma un reitinga
* Vietu pievienošana ceļojuma plānam
* Interaktīva karte ar Leaflet
* Maršruta līnija starp izvēlētajām vietām
* Ceļojuma plāna saglabāšana
* Saglabāto plānu apskate
* Ceļojuma plāna rediģēšana
* Ceļojuma plāna dzēšana
* Administratora vadības panelis
* Sistēmas statistika administratoram
* Tumšais un gaišais režīms
* Responsīvs dizains mobilajām ierīcēm
* PWA atbalsts

---

## Administratora piekļuve

Testēšanai var izmantot administratora kontu:

```text
E-pasts: admin@test.com
Parole: admin123
```

Administrators pēc pieslēgšanās redz papildus izvēlni **ADMIN**, kurā pieejams vadības panelis ar statistiku.

---

## Izmantotās tehnoloģijas

### Frontend

* Vue 3
* Vite
* Vue Router
* Leaflet
* Vue Leaflet
* HTML
* CSS
* JavaScript
* PWA manifest
* Service Worker

### Backend

* Laravel
* Laravel Sanctum
* PHP
* REST API
* Railway hosting

### Datu bāze

* PostgreSQL Railway vidē
* SQLite lokālai izstrādei
* Laravel migrations

### Versiju kontrole un izvietošana

* Git
* GitHub
* Vercel frontend izvietošanai
* Railway backend un datu bāzes izvietošanai

---

## Datu bāzes tabulas

Projektā tiek izmantotas vairākas datu bāzes tabulas:

* `users` — sistēmas lietotāji un administratori
* `cities` — ceļojuma pilsētas
* `places` — viesnīcas, restorāni un muzeji
* `plans` — lietotāju saglabātie ceļojuma plāni
* `personal_access_tokens` — Laravel Sanctum autentifikācijas tokeni
* `sessions` — sesiju dati
* `cache` — Laravel kešatmiņas dati
* `migrations` — migrāciju vēsture

Galvenās projekta tabulas ir `users`, `cities`, `places` un `plans`.

---

## Projekta struktūra

```text
travel-planner/
│
├── my-frontend/
│   ├── public/
│   │   ├── manifest.webmanifest
│   │   ├── sw.js
│   │   └── pwa-icon.svg
│   │
│   ├── src/
│   │   ├── components/
│   │   │   └── Navbar.vue
│   │   │
│   │   ├── views/
│   │   │   ├── Home.vue
│   │   │   ├── SavedPlans.vue
│   │   │   ├── About.vue
│   │   │   ├── Admin.vue
│   │   │   ├── Login.vue
│   │   │   └── Register.vue
│   │   │
│   │   ├── router/
│   │   │   └── index.js
│   │   │
│   │   ├── App.vue
│   │   └── main.js
│   │
│   ├── .env
│   ├── vercel.json
│   └── package.json
│
└── my-laravel-app/
    ├── app/
    ├── database/
    │   └── migrations/
    ├── routes/
    │   └── api.php
    ├── composer.json
    ├── Procfile
    └── .env
```

---

## Projekta palaišana lokāli

Lai projekts darbotos lokāli, jāpalaiž gan Laravel backend, gan Vue frontend.

---

## Backend palaišana

Atver termināli projekta galvenajā mapē un pārej uz backend mapi:

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

Backend lokāli darbosies adresē:

```text
http://127.0.0.1:8000
```

Backend pārbaude:

```text
http://127.0.0.1:8000/api/test
```

Datu bāzes aizpildīšana ar pilsētām un vietām:

```text
http://127.0.0.1:8000/api/seed-cities
http://127.0.0.1:8000/api/seed-places
```

---

## Frontend palaišana

Atver otru termināli un pārej uz frontend mapi:

```bash
cd my-frontend
```

Instalē JavaScript dependencies:

```bash
npm install
```

Palaid Vue projektu:

```bash
npm run dev
```

Frontend lokāli darbosies adresē:

```text
http://localhost:5173
```

Frontend `.env` failā jānorāda backend API adrese:

```env
VITE_API_URL=http://127.0.0.1:8000/api
```

---

## API maršruti

### Test

```text
GET /api/test
```

### Auth

```text
POST /api/register
POST /api/login
POST /api/logout
GET /api/user
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
PUT /api/plans/{id}
GET /api/my-plans
DELETE /api/plans/{id}
```

### Admin

```text
GET /api/admin/stats
```

Admin API maršruts ir aizsargāts ar autentifikāciju un pieejams tikai lietotājam ar `admin` lomu.

---

## Datu apstrāde

Sistēmā tiek veikta datu apstrāde vairākos veidos:

* ierakstu atlase no datu bāzes;
* pilsētu un vietu attēlošana frontend daļā;
* meklēšana pēc vietas nosaukuma;
* filtrēšana pēc vietas tipa;
* kārtošana pēc nosaukuma un reitinga;
* datu saglabāšana lietotāja ceļojuma plānā;
* datu rediģēšana;
* datu dzēšana;
* statistikas aprēķini administratora panelī;
* ierakstu grupēšana pēc vietas tipa un pilsētas.

---

## Saistītās tabulas un statistika

Administratora panelī tiek izmantota datu atlase no vairākām savstarpēji saistītām tabulām.

Piemēri:

* `cities` tiek sasaistīta ar `places`, lai parādītu vietu skaitu katrā pilsētā;
* `users` tiek sasaistīta ar `plans`, lai parādītu lietotāju saglabāto plānu skaitu;
* `places` tiek grupēta pēc tipa, lai parādītu viesnīcu, restorānu un muzeju skaitu.

Administratora panelī tiek attēlota šāda statistika:

* kopējais lietotāju skaits;
* administratoru skaits;
* pilsētu skaits;
* vietu skaits;
* saglabāto plānu skaits;
* vietas pēc tipa;
* vietas pēc pilsētas;
* lietotāji un viņu plānu skaits.

---

## Lietotāju lomas

Sistēmā ir divas galvenās lietotāju lomas:

### Lietotājs

Lietotājs var:

* reģistrēties;
* pieslēgties;
* izvēlēties pilsētu;
* apskatīt vietas;
* meklēt, filtrēt un kārtot vietas;
* pievienot vietas ceļojuma plānam;
* saglabāt ceļojuma plānu;
* apskatīt savus saglabātos plānus;
* rediģēt un dzēst savus plānus.

### Administrators

Administrators var:

* pieslēgties sistēmai;
* piekļūt administratora vadības panelim;
* apskatīt sistēmas statistiku;
* redzēt lietotāju, pilsētu, vietu un plānu skaitu;
* redzēt datu grupēšanu pēc tipa un pilsētas.

---

## Datu validācija

Projektā tiek izmantota datu validācija gan frontend, gan backend daļā.

Piemēri:

* reģistrācijas formā tiek pārbaudīts vārds, e-pasts un parole;
* parolei jābūt vismaz 6 simbolus garai;
* e-pastam jābūt korektā formātā;
* login formā jāpārbauda e-pasts un parole;
* plāna rediģēšanā tiek pārbaudīts nosaukums un vietu saraksts;
* backend daļā tiek izmantota Laravel `validate()` metode.

---

## OWASP drošības principu ievērošana

Projektā tiek ievēroti pamata OWASP drošības principi:

* paroles netiek glabātas kā parasts teksts, tās tiek šifrētas ar `Hash::make()`;
* autentifikācijai tiek izmantoti Laravel Sanctum tokeni;
* aizsargātie API maršruti izmanto `auth:sanctum` middleware;
* administratora panelis pieejams tikai lietotājam ar `admin` lomu;
* tiek veikta ievades datu validācija;
* lietotājs var piekļūt tikai saviem saglabātajiem plāniem;
* plāna rediģēšana un dzēšana tiek pārbaudīta pēc `user_id`.

---

## WCAG pieejamības principu ievērošana

Projektā tiek ievēroti pamata WCAG pieejamības principi:

* pogām un ievades laukiem ir skaidri nosaukumi;
* formām ir `label` elementi;
* attēliem tiek izmantots `alt` atribūts;
* tiek izmantots pietiekams krāsu kontrasts;
* vietne ir responsīva un pielāgojas dažādiem ekrāna izmēriem;
* navigācija ir vienkārša un saprotama;
* teksts ir salasāms gan tumšajā, gan gaišajā režīmā.

---

## PWA

Projektā ir ieviesti PWA elementi:

* `manifest.webmanifest`;
* `sw.js`;
* `pwa-icon.svg`;
* aplikācijas nosaukums un ikona;
* iespēja izmantot vietni kā progresīvu tīmekļa lietotni.

---

## Testēšana

### Test case 1 — Lietotāja reģistrācija

**Darbība:** lietotājs ievada vārdu, e-pastu un paroli, pēc tam nospiež pogu “Reģistrēties”.
**Sagaidāmais rezultāts:** sistēma izveido jaunu lietotāja kontu un pieslēdz lietotāju sistēmai.

### Test case 2 — Lietotāja pieslēgšanās

**Darbība:** lietotājs ievada pareizu e-pastu un paroli.
**Sagaidāmais rezultāts:** sistēma atļauj pieslēgties un novirza lietotāju uz sākuma lapu.

### Test case 3 — Vietas pievienošana plānam

**Darbība:** lietotājs izvēlas pilsētu un pievieno vairākas vietas savam ceļojuma plānam.
**Sagaidāmais rezultāts:** izvēlētās vietas parādās sadaļā “Mans plāns”.

### Test case 4 — Ceļojuma plāna saglabāšana

**Darbība:** lietotājs ievada plāna nosaukumu un nospiež pogu “Saglabāt plānu”.
**Sagaidāmais rezultāts:** plāns tiek saglabāts datu bāzē un redzams sadaļā “Saglabātie plāni”.

### Test case 5 — Administratora panelis

**Darbība:** administrators pieslēdzas sistēmai un atver sadaļu “ADMIN”.
**Sagaidāmais rezultāts:** administrators redz vadības paneli ar sistēmas statistiku.

---

## Lietošana

1. Lietotājs izveido kontu vai pieslēdzas sistēmai.
2. Sākuma lapā izvēlas pilsētu.
3. Apskata vietas, izmanto meklēšanu, filtrēšanu un kārtošanu.
4. Pievieno interesējošās vietas ceļojuma plānam.
5. Saglabā plānu.
6. Saglabāto plānu lapā var apskatīt, ielādēt, rediģēt un dzēst savus plānus.
7. Administrators var atvērt vadības paneli un apskatīt statistiku.

---

## GitHub

Projekta kods atrodas GitHub repozitorijā:

```text
https://github.com/Maiz333/travel-planner
```

---

## Autors

Maksims Koršunovs

Rīgas Valsts tehnikums
Datorikas nodaļa
Programma: Programmēšana

Projekts izveidots kā ceļojumu plānošanas tīmekļa lietotne, izmantojot Vue.js, Laravel un datu bāzi.
