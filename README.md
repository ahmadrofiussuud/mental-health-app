# SerenityHub (Next.js MVP)

Aplikasi kesehatan mental berbasis AI untuk sekolah, dimigrasikan ke Next.js & Serverless.

## ⚠️ PERINGATAN PENTING
**JANGAN GUNAKAN AKUN DEFAULT DI PRODUCTION!**
Seed data hanya untuk keperluan development. Ganti password segera atau gunakan fitur Admin untuk membuat akun baru yang aman.

## 🛠 Tech Stack
- **Framework**: Next.js 14+ (App Router)
- **Language**: TypeScript
- **Database**: MySQL (via Prisma)
- **Auth**: NextAuth.js v5 (Credentials)
- **AI**: Google Gemini (`gemini-2.0-flash`)
- **Rate Limit**: Upstash Redis

## 🚀 Getting Started

### 1. Install Dependencies
```bash
npm install
```

### 2. Setup Database (Development)
Pastikan MySQL berjalan. Copy `.env.example` ke `.env` dan isi credentials.

```bash
# Push schema ke database (Hanya Development)
npx prisma db push

# Seed data awal (Admin, Teacher, Student) - JANGAN DI PRODUCTION
npx prisma db execute --file prisma/seed.sql
```

> **Catatan:** `prisma db push` cocok untuk prototyping. Untuk production, gunakan migration flow.

### Setup Database (Production)
Jika ada perubahan schema, buat migration lokal dulu:
```bash
npx prisma migrate dev --name init
```
Lalu di Vercel (Build Command), gunakan `npx prisma migrate deploy`.

### 3. Run Development Server
```bash
npm run dev
```
Buka [http://localhost:3000](http://localhost:3000).

## 🔑 Default Accounts (Seed)

| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin** | `admin@example.com` | `password` |
| **Teacher** | `teacher@example.com` | `password` |
| **Student** | `student@example.com` | `password` |

## 📦 Deployment (Vercel)

1.  **Push ke GitHub**.
2.  **Import Project di Vercel**.
3.  **Set Environment Variables**:
    *   `DATABASE_URL`: Connection string MySQL (PlanetScale/RDS).
    *   `NEXTAUTH_SECRET`: Generate pakai `openssl rand -base64 32`.
    *   `NEXTAUTH_URL`: URL Vercel (e.g. `https://serenity-hub.vercel.app`).
    *   `GOOGLE_GEMINI_API_KEY`: API Key dari Google AI Studio.
    *   `UPSTASH_REDIS_REST_URL`: Dari Upstash Console.
    *   `UPSTASH_REDIS_REST_TOKEN`: Dari Upstash Console.

4.  **Build Command**:
    Disarankan menggunakan custom command untuk memastikan database migrasi berjalan aman:
    ```bash
    npx prisma migrate deploy && next build
    ```
    
    > **Penting**: Pastikan file migration (`prisma/migrations`) ikut ter-commit ke repo.

5.  **Install Command**: `npm install`.

## 🛡️ Security Notes
*   **Password Hashing**: Menggunakan `bcryptjs` (Cost 12).
*   **Rate Limiting**: Aktif untuk endpoint AI (10 req/menit per user) menggunakan Upstash Redis.
*   **RBAC**: Middleware membatasi akses route admin/teacher/student.
*   **IDOR Protection**: Teacher dashboard membatasi view hanya ke student (MVP: currently listing all, should be refined to class-based).
*   **Audit Logging**: Endpoint sensitif mencatat log ke tabel `AuditLog`.

## 🔧 Troubleshooting
*   **Database Error**: Pastikan connection string di `DATABASE_URL` benar (termasuk port dan user/pass).
*   **Prisma Error**: Jika `P1012`, cek `prisma/schema.prisma` dan pastikan konfigurasi `url` mengarah ke `env("DATABASE_URL")`.
*   **NextAuth Error**: Pastikan `NEXTAUTH_SECRET` dan `NEXTAUTH_URL` diset di Vercel.
*   **Gemini 429**: Kuota API habis atau rate limit terlampaui. Cek dashboard Google AI Studio.
