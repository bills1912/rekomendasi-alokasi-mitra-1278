# 📍 Seemitra — Sistem Manajemen Pengalokasian Wilayah Kerja Mitra

<p align="center">
  <img src="public/img/bps-gusitv2.png" alt="BPS Logo" width="200"/>
</p>

Seemitra adalah aplikasi berbasis web yang dibangun menggunakan **Laravel 11** untuk membantu *Subject Matter* BPS (Badan Pusat Statistik) dalam mengelola pengalokasian mitra statistik pada kegiatan survei dan sensus. Aplikasi ini menyediakan fitur peta interaktif, rekomendasi alokasi mitra berbasis lokasi, manajemen honor mitra, serta pembuatan dokumen SPK dan BAST secara otomatis.

---

## ✨ Fitur Utama

- **Dashboard Peta Interaktif** — Visualisasi persebaran mitra menggunakan Leaflet.js dengan clustering marker.
- **Alokasi Mitra Survei** — Rekomendasi alokasi mitra ke blok sensus (BS) atau wilayah kerja statistik (Wilkerstat) berdasarkan kedekatan geografis.
- **Alokasi Mitra Sensus** — Pengalokasian mitra untuk kegiatan sensus berdasarkan SLS (Satuan Lingkungan Setempat).
- **Manajemen Rate Honor** — Penetapan dan pengelolaan honor mitra per kegiatan survei/sensus.
- **Generate Dokumen Otomatis** — Pembuatan Surat Perintah Kerja (SPK) dan Berita Acara Serah Terima (BAST) dalam format `.docx`.
- **Menu Master** — Pengelolaan data kegiatan survei/sensus, daftar mitra, pengguna, SBML (Standar Biaya Masukan Lainnya), dan status alokasi.
- **Rekap Honor Mitra** — Rekapitulasi total honor mitra per bulan/kegiatan beserta filter dinamis.
- **Autentikasi** — Login menggunakan email/password, Google OAuth, dan Facebook OAuth.
- **Manajemen Pengguna** — Pengaturan peran pengguna (Subject Matter, Admin, Anggota).

---

## 🛠️ Teknologi yang Digunakan

| Layer | Teknologi |
|---|---|
| Backend | Laravel 11, PHP >= 8.2 |
| Frontend | Bootstrap 5, Alpine.js, Tailwind CSS |
| Peta | Leaflet.js, Leaflet MarkerCluster, Leaflet Awesome Markers |
| Database | MySQL |
| Tabel Interaktif | DataTables, Select2 |
| Export Dokumen | PhpOffice/PhpWord |
| Import Excel | Maatwebsite/Excel |
| Notifikasi | RealRashid/SweetAlert |
| Auth Sosial | Laravel Socialite (Google, Facebook) |
| Build Tool | Vite |

---

## ⚙️ Persyaratan Sistem

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL
- Extension PHP: `intl`, `zip`, `gd`, `mbstring`, `xml`

---

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/username/seemitra.git
cd seemitra
```

### 2. Install Dependensi PHP

```bash
composer install
```

### 3. Install Dependensi Node.js

```bash
npm install
```

### 4. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

Kemudian sesuaikan konfigurasi database dan OAuth pada file `.env`:

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

### 5. Migrasi Database

```bash
php artisan migrate
```

### 6. Build Asset Frontend

```bash
# Development
npm run dev

# Production
npm run build
```

### 7. Jalankan Server

```bash
php artisan serve
```

Aplikasi dapat diakses di `http://localhost:8000`.

---

## 📁 Struktur Direktori Utama

```
seemitra/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── IPDSProjectController.php       # Alokasi mitra survei & auth sosial
│   │   │   ├── IPDSProjectSLSController.php    # Alokasi mitra sensus
│   │   │   ├── HonorMitraController.php        # Honor, SPK, dan BAST
│   │   │   ├── MenuMasterController.php        # Menu master (kegiatan, mitra, SBML)
│   │   │   └── KeuanganAlokasiMitraController.php # Rekap honor
│   │   └── Middleware/
│   ├── Models/                                 # Eloquent Models
│   └── Providers/
├── database/
│   └── migrations/
├── public/
│   ├── assets/doc_template/                    # Template SPK & BAST (.docx)
│   ├── js/                                     # GeoJSON & JavaScript kustom
│   └── img/
├── resources/
│   └── views/
│       ├── alokasi/                            # Halaman alokasi mitra
│       ├── keuangan/                           # Rekap honor mitra
│       ├── master_menu/                        # Halaman menu master
│       ├── mitra_view/                         # Tampilan sisi mitra
│       └── rate_honor/                         # Form input honor
└── routes/
    └── web.php
```

---

## 👤 Peran Pengguna

| Peran | Akses |
|---|---|
| **Admin** | Akses penuh: kelola pengguna, mitra, kegiatan, SBML, status alokasi |
| **Subject Matter (SM)** | Alokasi mitra, input honor, generate SPK & BAST, rekap honor |
| **Mitra** | Melihat kalender kegiatan dan daftar survei/sensus yang diikuti |

---

## 📄 Template Dokumen

Letakkan file template dokumen Word (`.docx`) pada direktori:

```
public/assets/doc_template/
├── SPK_template.docx
└── BAST_template.docx
```

Template menggunakan placeholder berbasis `${variable}` yang akan diisi secara otomatis saat generate dokumen.

---

## 🗺️ Data GeoJSON

Data wilkerstat (blok sensus, desa, kecamatan) dalam format GeoJSON disimpan di direktori `public/js/`. Pastikan file berikut tersedia:

- `1278_finalbs_2023_sem2.geojson` — Data blok sensus
- `final_desa_202311278.geojson` — Data desa

---

## 📦 Package Utama

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

## 🔒 Keamanan

- CSRF Protection aktif pada semua form
- Autentikasi berbasis session Laravel
- Middleware otorisasi untuk memisahkan akses Admin, SM, dan Mitra
- Password di-hash menggunakan Bcrypt

---

## 📝 Lisensi

Proyek ini dikembangkan untuk keperluan internal **BPS Kota Gunungsitoli (1278)**. Seluruh hak cipta dilindungi.

---

## 🤝 Kontribusi

Kontribusi terbuka untuk internal tim pengembang BPS. Silakan buat *branch* baru, lakukan perubahan, dan ajukan *pull request* untuk direview.

---

<p align="center">
  Dikembangkan dengan ❤️ untuk BPS Kota Gunungsitoli &copy; 2024
</p>