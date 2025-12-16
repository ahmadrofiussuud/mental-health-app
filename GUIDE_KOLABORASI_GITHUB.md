# Panduan Kolaborasi GitHub - MindCare App

Panduan ini menjelaskan cara mengupload project ini ke GitHub dan mengundang teman untuk berkolaborasi (Push/Pull).

## Langkah 1: Upload Project ke GitHub (Untuk Kamu/Pemilik)

1.  **Login ke GitHub**: Buka [github.com](https://github.com) dan login.
2.  **Buat Repository Baru**:
    *   Klik tombol **+** di pojok kanan atas -> **New repository**.
    *   Nama Repository: `mindcare-app` (atau bebas).
    *   Pilih **Private** (agar tidak bisa dilihat orang asing) atau **Public**.
    *   Jangan centang "Add a README file" (karena kita akan upload project yang sudah ada).
    *   Klik **Create repository**.
3.  **Hubungkan Project Lokal ke GitHub**:
    *   Buka terminal di folder project ini (`c:\laragon\www\Project-Kesehatan-mental`).
    *   Jalankan perintah berikut (ganti `USERNAME` dengan username GitHub-mu):
        ```bash
        git init
        git add .
        git commit -m "Upload pertama project MindCare"
        git branch -M main
        git remote add origin https://github.com/USERNAME/mindcare-app.git
        git push -u origin main
        ```

## Langkah 2: Undang Teman Sebagai Collaborator

Agar temanmu bisa `push` (upload codingan dia), kamu harus mengundangnya:
1.  Buka repository yang baru kamu buat di GitHub.
2.  Masuk ke tab **Settings** -> **Collaborators**.
3.  Klik **Add people**.
4.  Masukkan **Email** atau **Username GitHub** temanmu.
5.  Temanmu akan dapat email undangan, dia harus klik **Accept Invitation**.

---

## Langkah 3: Cara Kerja Untuk Temanmu (Partner)

Kirimkan panduan ini ke temanmu.

### 1. Clone (Download) Project
Temanmu harus membuka terminal di laptopnya (folder `laragon/www` atau `htdocs`) dan jalankan:
```bash
git clone https://github.com/USERNAME/mindcare-app.git
cd mindcare-app
```

### 2. Setup Awal (Hanya Sekali)
Temanmu perlu menginstall hal-hal berikut agar web bisa jalan di laptopnya:
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
```
*Catatan: Temanmu mungkin perlu setting database di `.env` agar sesuai dengan laptopnya.*

### 3. Cara Update Codingan (Pull & Push)

**Setiap kali mau mulai ngoding (Wajib! Agar tidak bentrok):**
Ambil codingan terbaru dari teman:
```bash
git pull origin main
```

**Setelah selesai ngoding:**
Upload hasil kerjaan ke GitHub:
```bash
git add .
git commit -m "Menambahkan fitur X atau memperbaiki bug Y"
git push origin main
```

## Tips Anti Bentrok (Conflict)
1.  **Komunikasi**: Bilang "Aku lagi ngerjain Controller A ya", biar temanmu ga ngerjain file yang sama.
2.  **Sering Pull**: Sering-sering `git pull origin main` biar project di laptopmu selalu update.
