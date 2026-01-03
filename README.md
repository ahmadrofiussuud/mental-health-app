# 🧠 SerenityHub - Mental Health App

## 📖 Introduction
SerenityHub adalah aplikasi web untuk mendukung kesehatan mental siswa di sekolah. Aplikasi ini menyediakan fitur jurnal pribadi, mood tracking, konseling dengan guru BK, dan AI-powered analysis untuk membantu siswa mengelola kesehatan mental mereka.

## 🎯 Features
- ✍️ **Personal Journal** - Siswa dapat menulis jurnal harian (publik atau anonim)
- 😊 **Mood Detection** - Track mood dengan AI (face & voice analysis)
- 💬 **Student-Teacher Chat** - Komunikasi langsung dengan guru BK
- 🤖 **AI Analysis** - Analisa psikologis otomatis menggunakan Gemini AI
- 📊 **Teacher Dashboard** - Monitor kesehatan mental siswa secara keseluruhan
- 🔒 **Privacy First** - Opsi jurnal anonim untuk privasi siswa

## ⚙️ Tech Stack
- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Blade Templates + AlpineJS + TailwindCSS
- **Database**: MySQL
- **AI**: Google Gemini API
- **Build Tool**: Vite

---

## 🚀 Getting Started

### Prerequisites
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL/MariaDB
- Git

### Installation Steps

#### 1️⃣ Clone Repository
```bash
git clone <repository-url>
cd Project-Kesehatan-mental
```

#### 2️⃣ Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

#### 3️⃣ Environment Configuration
```bash
# Copy environment file
copy .env.example .env
```

**Edit `.env` file:**
```env
APP_NAME=SerenityHub
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mental_health_db
DB_USERNAME=root
DB_PASSWORD=

# Get API key from: https://makersuite.google.com/app/apikey
GEMINI_API_KEY=your_gemini_api_key_here
```

#### 4️⃣ Generate Application Key
```bash
php artisan key:generate
```

#### 5️⃣ Create Database
Buat database baru di MySQL:
```sql
CREATE DATABASE mental_health_db;
```

#### 6️⃣ Run Migrations & Seed Database
```bash
# Run migrations
php artisan migrate

# Seed database with sample data
php artisan db:seed
```

#### 7️⃣ Compile Assets
```bash
# For development
npm run dev

# OR for production
npm run build
```

#### 8️⃣ Start Development Server
```bash
php artisan serve
```

Aplikasi akan berjalan di: **http://127.0.0.1:8000**

---

## 👤 Default Login Credentials

Setelah seeding, gunakan kredensial berikut untuk login:

### Student Account
- **Email**: `student@school.com`
- **Password**: `password`
- **Role**: Student (Azid)
- **Features**: Journal writing, mood check, chat with teacher

### Teacher Account
- **Email**: `teacher@school.com`
- **Password**: `password`
- **Role**: Teacher (Guru BK)
- **Features**: Monitor students, AI analysis, conflict detection

### Admin Account
- **Email**: `admin@school.com`
- **Password**: `password`
- **Role**: Administrator
- **Features**: User management, system configuration

---

## 📦 Database Seeding

Seeder akan otomatis membuat:
- ✅ 3 default users (student, teacher, admin)
- ✅ 4 sample journals untuk student
- ✅ Sample data dengan berbagai mood (happy, sad, calm, neutral)

Untuk reset database dan seed ulang:
```bash
php artisan migrate:fresh --seed
```

⚠️ **Warning**: Perintah ini akan menghapus semua data yang ada!

---

## 🛠️ Development

### Run Development Server
```bash
# Run Laravel server + Vite (hot reload)
npm run dev

# In another terminal
php artisan serve
```

### Clear Cache (Jika ada masalah)
```bash
php artisan optimize:clear
php artisan view:clear
php artisan route:clear
php artisan config:clear
```

### Compile Assets for Production
```bash
npm run build
```

---

## 📁 Project Structure

```
Project-Kesehatan-mental/
├── app/
│   ├── Http/Controllers/
│   │   ├── Student/DashboardController.php
│   │   ├── Teacher/DashboardController.php
│   │   ├── JournalController.php
│   │   └── MoodDetectionController.php
│   └── Models/
│       ├── User.php
│       └── Journal.php
├── database/
│   ├── migrations/
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── MainSeeder.php
├── resources/
│   ├── views/
│   │   ├── student/dashboard.blade.php
│   │   ├── teacher/dashboard.blade.php
│   │   └── journal/index.blade.php
│   └── css/
└── public/
    └── images/
```

---

## 🤝 Team Collaboration

### Untuk Partner/Teman yang Baru Clone:

1. **Clone repository**
   ```bash
   git clone <repository-url>
   cd Project-Kesehatan-mental
   ```

2. **Jalankan setup lengkap**
   ```bash
   composer install
   npm install
   copy .env.example .env
   php artisan key:generate
   ```

3. **Edit `.env`** - Sesuaikan database & Gemini API key

4. **Setup database**
   ```bash
   php artisan migrate --seed
   ```

5. **Compile & run**
   ```bash
   npm run dev
   php artisan serve
   ```

6. **Login** menggunakan kredensial di atas

---

## 🔑 Gemini API Key Setup

1. Kunjungi: https://makersuite.google.com/app/apikey
2. Sign in dengan Google Account
3. Klik "Create API Key"
4. Copy API key yang dihasilkan
5. Paste ke `.env`:
   ```env
   GEMINI_API_KEY=AIzaSy...
   ```

---

## 📝 Notes

- Semua password default adalah `password` (untuk development only)
- Jangan lupa menambahkan Gemini API key untuk fitur AI
- Gunakan `php artisan migrate:fresh --seed` untuk reset database
- Assets (gambar school bus, logo, dll) sudah ter-commit di `/public/images`

---

## 🐛 Troubleshooting

### Masalah: Dashboard kosong/broken
**Solusi:**
```bash
git pull
php artisan optimize:clear
npm run dev
# Hard reload browser: Ctrl+Shift+R
```

### Masalah: Error 500 / Gemini API
**Solusi:**
- Pastikan `GEMINI_API_KEY` sudah diisi di `.env`
- Cek API key masih valid
- Clear config: `php artisan config:clear`

### Masalah: Assets tidak muncul
**Solusi:**
```bash
npm install
npm run build
php artisan storage:link
```

---

## 📜 License
This project is for educational purposes.

---

**Happy Coding! 🚀**