# 📍 Seemitra — Partner Work Area Allocation Management System

<p align="center">
  <img src="public/img/bps-gusitv2.png" alt="BPS Logo" width="200"/>
</p>

Seemitra is a web-based application built with **Laravel 11** designed to assist *Subject Matter* officers at BPS (Statistics Indonesia) in managing the allocation of statistical field partners (*mitra*) for survey and census activities. The system provides an interactive map, location-based partner allocation recommendations, honor/payment management, and automated document generation for SPK and BAST letters.

---

## ✨ Key Features

- **Interactive Map Dashboard** — Visualize partner distribution using Leaflet.js with marker clustering.
- **Survey Partner Allocation** — Recommend partner assignments to census blocks (BS) or statistical working areas (Wilkerstat) based on geographic proximity.
- **Census Partner Allocation** — Allocate partners for census activities based on SLS (Sub-village Statistical Units).
- **Honor Rate Management** — Set and manage partner payment rates per survey/census activity.
- **Automated Document Generation** — Generate Work Order Letters (SPK) and Handover Reports (BAST) in `.docx` format.
- **Master Menu** — Manage survey/census activities, partner lists, users, SBML (budget standards), and allocation statuses.
- **Honor Summary** — Monthly/activity-based partner payment recapitulation with dynamic filtering.
- **Authentication** — Login via email/password, Google OAuth, and Facebook OAuth.
- **User Management** — Role-based access control (Subject Matter, Admin, Member).

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 11, PHP >= 8.2 |
| Frontend | Bootstrap 5, Alpine.js, Tailwind CSS |
| Maps | Leaflet.js, Leaflet MarkerCluster, Leaflet Awesome Markers |
| Database | MySQL |
| Interactive Tables | DataTables, Select2 |
| Document Export | PhpOffice/PhpWord |
| Excel Import | Maatwebsite/Excel |
| Notifications | RealRashid/SweetAlert |
| Social Auth | Laravel Socialite (Google, Facebook) |
| Build Tool | Vite |

---

## ⚙️ System Requirements

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL
- PHP Extensions: `intl`, `zip`, `gd`, `mbstring`, `xml`

---

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/username/seemitra.git
cd seemitra
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node.js Dependencies

```bash
npm install
```

### 4. Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Then update the database and OAuth credentials in your `.env` file:

```env
APP_NAME=Seemitra
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rekomendasi_alokasi_mitra
DB_USERNAME=root
DB_PASSWORD=

# Google OAuth
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=http://localhost/auth/google/callback

# Facebook OAuth
FACEBOOK_CLIENT_ID=your-facebook-client-id
FACEBOOK_CLIENT_SECRET=your-facebook-client-secret
FACEBOOK_REDIRECT_URI=http://localhost/auth/facebook/callback
```

### 5. Run Database Migrations

```bash
php artisan migrate
```

### 6. Build Frontend Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 7. Start the Development Server

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`.

---

## 📁 Project Structure

```
seemitra/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── IPDSProjectController.php          # Survey partner allocation & social auth
│   │   │   ├── IPDSProjectSLSController.php       # Census partner allocation
│   │   │   ├── HonorMitraController.php           # Honor management, SPK & BAST generation
│   │   │   ├── MenuMasterController.php           # Master menu (activities, partners, SBML)
│   │   │   └── KeuanganAlokasiMitraController.php # Honor recapitulation
│   │   └── Middleware/
│   ├── Models/                                    # Eloquent Models
│   └── Providers/
├── database/
│   └── migrations/
├── public/
│   ├── assets/doc_template/                       # SPK & BAST Word templates
│   ├── js/                                        # GeoJSON & custom JavaScript
│   └── img/
├── resources/
│   └── views/
│       ├── alokasi/                               # Partner allocation pages
│       ├── keuangan/                              # Honor recapitulation pages
│       ├── master_menu/                           # Master menu pages
│       ├── mitra_view/                            # Partner-facing views
│       └── rate_honor/                            # Honor input forms
└── routes/
    └── web.php
```

---

## 👤 User Roles

| Role | Access |
|---|---|
| **Admin** | Full access: manage users, partners, activities, SBML, allocation statuses |
| **Subject Matter (SM)** | Allocate partners, input honor, generate SPK & BAST, view honor summaries |
| **Partner (Mitra)** | View activity calendar and list of assigned surveys/censuses |

---

## 📄 Document Templates

Place the Word document templates (`.docx`) in the following directory:

```
public/assets/doc_template/
├── SPK_template.docx
└── BAST_template.docx
```

Templates use `${variable}` placeholder syntax which is automatically populated during document generation.

---

## 🗺️ GeoJSON Data

Statistical working area data (census blocks, villages, sub-districts) in GeoJSON format should be placed in `public/js/`. Ensure the following files are present:

- `1278_finalbs_2023_sem2.geojson` — Census block data
- `final_desa_202311278.geojson` — Village boundary data

---

## 📦 Key Packages

```json
{
  "laravel/framework": "^11.0",
  "laravel/socialite": "^5.11",
  "maatwebsite/excel": "^3.1",
  "phpoffice/phpword": "^1.2",
  "kwn/number-to-words": "^2.8",
  "realrashid/sweet-alert": "^7.1"
}
```

---

## 🔒 Security

- CSRF protection enabled on all forms
- Laravel session-based authentication
- Authorization middleware separating Admin, SM, and Partner access
- Passwords hashed using Bcrypt

---

## 📝 License

This project was developed for internal use at **BPS Kota Gunungsitoli (1278)**. All rights reserved.

---

## 🤝 Contributing

Contributions are open to the internal BPS development team. Please create a new branch, make your changes, and submit a pull request for review.

---

<p align="center">
  Built with ❤️ for BPS Kota Gunungsitoli &copy; 2024
</p>