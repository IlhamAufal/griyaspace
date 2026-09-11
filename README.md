# GriyaSpace - Sistem Manajemen Ruangan & Booking

GriyaSpace adalah aplikasi web untuk manajemen ruangan dan pengajuan booking, dibangun dengan **Laravel 12**, **Tailwind CSS v4**, dan **Alpine.js**. Aplikasi ini dirancang untuk institusi seperti universitas atau organisasi yang membutuhkan sistem peminjaman ruangan yang terstruktur.

## Fitur Utama

- **Manajemen Ruangan** - CRUD ruangan dengan foto, kapasitas, lokasi, fasilitas, dan jam operasional
- **Pengajuan Booking** - Pengguna organisasi dapat mengajukan peminjaman ruangan
- **Alur Persetujuan** - Admin dapat menyetujui/menolak pengajuan, dengan generate surat izin (PDF)
- **Kalender** - Visualisasi booking dalam tampilan kalender
- **Manajemen Organisasi** - Data organisasi/ormawa pengguna
- **Manajemen Pengguna & Role** - Dua role: Admin dan Organisasi
- **Riwayat & Audit Log** - Pelacakan riwayat booking dan log aktivitas
- **Dashboard Berbasis Role** - Dashboard berbeda untuk Admin dan Organisasi

## Requirements

- **PHP 8.2+**
- **Composer** (PHP dependency manager)
- **Node.js 18+** dan **npm** (untuk frontend assets)
- **MySQL** (database default)
- **Storage link** untuk file upload

### Cek Environment

```bash
php -v
composer -V
node -v
npm -v
```

## Quick Start Installation

### Step 1: Clone Repository

```bash
git clone <url-repo>
cd griyaspace
```

### Step 2: Install PHP Dependencies

```bash
composer install
```

### Step 3: Install Node.js Dependencies

```bash
npm install
```

### Step 4: Environment Configuration

```bash
cp .env.example .env
```

**Windows:**

```bash
copy .env.example .env
```

### Step 5: Generate Application Key

```bash
php artisan key:generate
```

### Step 6: Konfigurasi Database

Update file `.env` dengan kredensial database kamu:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=griyaspace
DB_USERNAME=root
DB_PASSWORD=
```

Buat database:

```bash
mysql -u root -p -e "CREATE DATABASE griyaspace;"
```

### Step 7: Jalankan Migrations & Seeders

```bash
php artisan migrate
php artisan db:seed
```

Ini akan membuat:
- **Admin**: `admin@griyaspace.test` / `password`
- **Organisasi**: `ormawa@griyaspace.test` / `password`
- 3 sample ruangan

### Step 8: Storage Link

```bash
php artisan storage:link
```

## Menjalankan Aplikasi

### Development Mode (Recommended)

```bash
composer run dev
```

Perintah ini menjalankan secara bersamaan:
- Laravel development server (http://localhost:8000)
- Vite dev server untuk HMR
- Queue worker
- Log monitoring (Pail)

**Akses aplikasi di:** [http://localhost:8000](http://localhost:8000)

### Manual Development

**Terminal 1 - Laravel Server:**
```bash
php artisan serve
```

**Terminal 2 - Frontend Assets:**
```bash
npm run dev
```

### Build untuk Production

```bash
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
```

Update `.env` untuk production:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

## Deployment (Vercel)

Project ini sudah dikonfigurasi untuk deployment di Vercel via `vercel.json`. Build command yang digunakan:

```bash
composer install --no-dev --no-scripts && npm ci && npm run build
```

## Testing

```bash
composer run test
```

Atau manual:

```bash
php artisan test
```

## Available Commands

### Composer Scripts

```bash
composer run dev       # Start development environment
composer run test      # Run tests
```

### NPM Scripts

```bash
npm run dev           # Start Vite dev server
npm run build         # Build untuk production
npm run lint          # Lint JavaScript
npm run format        # Format code
```

### Artisan Commands

```bash
php artisan serve                    # Start development server
php artisan migrate                  # Run migrations
php artisan migrate:fresh --seed     # Fresh migrations + seeding
php artisan db:seed                  # Run seeders
php artisan key:generate             # Generate app key
php artisan storage:link             # Create storage symlink
php artisan optimize:clear           # Clear all caches
php artisan route:list               # List all routes
```

## Project Structure

```
griyaspace/
├── app/
│   ├── Console/Commands/         # Custom artisan commands
│   ├── Enums/                    # Enumerasi (BookingStatus)
│   ├── Exceptions/               # Custom exceptions
│   ├── Helpers/                  # Helper functions
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/            # Admin dashboard controller
│   │   │   └── Organization/     # Org dashboard controller
│   │   └── Middleware/           # EnsureRole, EnsureOwnership
│   ├── Models/                  # Eloquent models
│   └── Services/                # BookingService, PermitPdfService
├── database/
│   ├── migrations/              # Database schema
│   └── seeders/                 # Database seeders
├── resources/
│   ├── css/                     # Stylesheets (Tailwind)
│   ├── js/                      # JavaScript (Alpine.js)
│   └── views/                   # Blade templates
│       ├── components/          # Reusable components
│       ├── layouts/             # Layout templates
│       └── pages/               # Page views
├── routes/
│   └── web.php                  # Web routes
├── vercel.json                  # Vercel deployment config
└── vite.config.js               # Vite configuration
```

## Models

| Model | Deskripsi |
|-------|-----------|
| `User` | Pengguna sistem (admin & organisasi) |
| `Role` | Role pengguna (admin, organization) |
| `Organization` | Data organisasi/ormawa |
| `Room` | Data ruangan |
| `RoomPhoto` | Foto ruangan |
| `Booking` | Pengajuan booking ruangan |
| `BookingDocument` | Dokumen lampiran booking |
| `BookingHistory` | Riwayat perubahan status booking |
| `Permit` | Surat izin penggunaan ruangan (PDF) |
| `AuditLog` | Log aktivitas sistem |

## Akses & Role

### Admin
- Dashboard admin dengan statistik
- Kelola ruangan (CRUD + foto)
- Kelola organisasi
- Kelola pengguna & role
- Konfirmasi/penolakan booking
- Generate surat izin (PDF)

### Organisasi
- Dashboard organisasi
- Lihat daftar ruangan
- Ajukan booking baru
- Lihat status & riwayat booking
- Download surat izin

## Troubleshooting

### "Class not found" errors
```bash
composer dump-autoload
```

### Permission errors on storage/bootstrap/cache
```bash
chmod -R 775 storage bootstrap/cache
```

### NPM build errors
```bash
rm -rf node_modules package-lock.json
npm install
```

### Clear all caches
```bash
php artisan optimize:clear
```

### Database connection errors
- Cek kredensial database di `.env`
- Pastikan database server running
- Pastikan database sudah dibuat

## Tech Stack

- **Backend:** Laravel 12, PHP 8.2+
- **Frontend:** Tailwind CSS v4, Alpine.js, Vite
- **Database:** MySQL
- **PDF Generation:** DomPDF, FPDF/FPDI
- **QR Code:** endroid/qr-code
- **Deployment:** Vercel

## License

MIT
