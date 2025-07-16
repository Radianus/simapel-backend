# SIMAPEL Sumba Barat Daya

![SIMAPEL Logo (Optional - replace with your project logo)](https://via.placeholder.com/150/007bff/FFFFFF?text=SIMAPEL+SBD)

Sistem Informasi Manajemen Data Pembangunan (SIMAPEL) Sumba Barat Daya adalah aplikasi web yang dirancang untuk membantu Pemerintah Kabupaten Sumba Barat Daya dalam merencanakan, memantau, dan mengelola proyek-proyek pembangunan secara lebih efisien dan transparan. Aplikasi ini mengintegrasikan data proyek dengan visualisasi geografis menggunakan peta digital, serta menyediakan fitur pelaporan dan manajemen pengguna.

## Fitur Utama

SIMAPEL Sumba Barat Daya hadir dengan berbagai fitur esensial untuk manajemen pembangunan yang efektif:

* **Manajemen Proyek (CRUD)**: Kemampuan lengkap untuk Menambah, Melihat, Mengubah, dan Menghapus data proyek pembangunan.
* **Visualisasi Peta Interaktif**: Menampilkan sebaran lokasi proyek di peta digital menggunakan Leaflet.js, dengan *marker* berwarna berdasarkan status proyek (On-Track, Terlambat, Selesai).
* **Peta di Formulir Input**: Memudahkan entri lokasi proyek dengan mengklik langsung pada peta di halaman tambah dan edit proyek.
* **Dashboard Statistik**: Menyajikan ringkasan visual dan statistik kunci pembangunan (total proyek, anggaran, progres, distribusi per sektor/status) untuk mendukung pengambilan keputusan strategis.
* **Filter dan Pencarian Canggih**: Memungkinkan pencarian dan pemfilteran data proyek berdasarkan nama, dinas penanggung jawab, sektor, atau status.
* **Manajemen Dokumen & Foto Proyek**: Fitur unggah multi-foto dan dokumen (PDF, DOCX, XLSX) ke setiap proyek, dengan pratinjau *client-side*, penyimpanan yang terorganisir, dan akses unduh yang terkontrol.
* **Manajemen Pengguna & Peran (RBAC)**: Modul untuk mengelola pengguna sistem (tambah, edit, hapus) serta memberikan peran dan izin (menggunakan Spatie Laravel Permission) untuk mengontrol hak akses terhadap fitur-fitur aplikasi.
* **Ekspor Data ke Excel**: Kemampuan untuk mengekspor data proyek ke format Excel (.xlsx) untuk keperluan pelaporan dan analisis *offline*.
* **Antarmuka Admin Responsif**: Tampilan admin yang rapi dan responsif dengan navigasi *sidebar* menggunakan Tailwind CSS dan Alpine.js.

## Teknologi yang Digunakan

* **Backend**: Laravel (PHP Framework)
* **Database**: PostgreSQL (dengan dukungan spasial PostGIS untuk data geografis)
* **Frontend**: Blade Templates, Tailwind CSS, Alpine.js (untuk interaktivitas UI)
* **Peta**: Leaflet.js
* **Ekspor Data**: Maatwebsite/Laravel-Excel
* **Manajemen Peran & Izin**: Spatie/Laravel-Permission

## Panduan Instalasi (Lokal)

Ikuti langkah-langkah di bawah ini untuk menjalankan proyek SIMAPEL di lingkungan pengembangan lokal Anda (direkomendasikan menggunakan **Laragon** untuk Windows).

**Pastikan Anda memiliki:**
* PHP (v8.1 atau lebih tinggi)
* Composer
* Node.js & NPM
* PostgreSQL (dengan ekstensi PostGIS aktif di database Anda)
* Laragon (untuk lingkungan server lokal di Windows)

1.  **Clone Repositori:**
    ```bash
    git clone [URL_REPOSITORI_ANDA] simapel-backend
    cd simapel-backend
    ```

2.  **Instal Dependensi Composer:**
    ```bash
    composer install
    ```

3.  **Konfigurasi Environment (`.env`):**
    * Buat file `.env` dari `.env.example`:
        ```bash
        cp .env.example .env
        ```
    * Buka file `.env` dan sesuaikan pengaturan database Anda untuk PostgreSQL:
        ```dotenv
        DB_CONNECTION=pgsql
        DB_HOST=127.0.0.1
        DB_PORT=5432
        DB_DATABASE=simapel_db # Ganti dengan nama database Anda
        DB_USERNAME=simapel_user # Ganti dengan username database Anda
        DB_PASSWORD=password_anda_disini # Ganti dengan password database Anda

        APP_URL=[http://127.0.0.1:8000](http://127.0.0.1:8000) # SESUAIKAN dengan URL Laragon/akses Anda (contoh: http://localhost, http://nama_proyek.test)
        ```

4.  **Buat Kunci Aplikasi:**
    ```bash
    php artisan key:generate
    ```

5.  **Jalankan Migrasi Database dan Seeder:**
    * **Penting:** Pastikan database `simapel_db` (atau nama yang Anda gunakan) sudah ada di PostgreSQL Anda dan ekstensi PostGIS sudah diaktifkan di dalamnya (`CREATE EXTENSION postgis;`).
    * Perintah ini akan menghapus semua tabel dan membuat ulang, lalu mengisi data *dummy* dan peran/izin default.
    ```bash
    php artisan migrate:fresh --seed
    ```

6.  **Buat Folder Penyimpanan Manual & Sesuaikan `filesystems.php`:**
    * Di File Explorer, navigasi ke `storage/app/` di root proyek Anda.
    * Pastikan ada folder `private` di dalamnya, lalu pastikan ada folder `public` di dalam `private`. Jadi, strukturnya: `storage/app/private/public/`. Jika `private` tidak ada, buatlah.
    * Di dalam `public` (yang berada di `private`), buat folder `project_media`, lalu di dalamnya buat `photos` dan `documents`. Jadi, `storage/app/private/public/project_media/photos` dan `storage/app/private/public/project_media/documents`.
    * **Buka `config/filesystems.php`** dan pastikan konfigurasi disk `public` menunjuk ke lokasi tersebut:
        ```php
        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/private/public'), # Sesuaikan dengan lokasi penyimpanan Anda
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],
        ```

7.  **Buat Symlink Storage (Penting untuk Akses File Publik):**
    * **Buka Command Prompt/PowerShell sebagai ADMINISTRATOR.**
    * Navigasi ke *root* folder proyek Anda: `cd C:\laragon\www\laravel\simapel-backend`
    * Jalankan perintah ini:
        ```bash
        php artisan storage:link
        ```
        Anda harus melihat pesan "The [...] link has been connected."

8.  **Instal Dependensi NPM dan Kompilasi Aset Frontend:**
    ```bash
    npm install
    npm run dev
    # Atau untuk mode pengawasan perubahan otomatis:
    # npm run watch
    ```

9.  **Jalankan Server Laravel:**
    ```bash
    php artisan serve
    ```

## Penggunaan Aplikasi

Setelah semua langkah instalasi selesai:

1.  Akses aplikasi di browser Anda melalui URL Laravel (biasanya `http://127.0.0.1:8000`).
2.  Anda akan langsung diarahkan ke halaman login.

### Kredensial Login Default

Setelah menjalankan `php artisan migrate:fresh --seed`, Anda akan memiliki dua akun pengguna default:

* **Super Admin:**
    * Email: `admin@simapel.com`
    * Password: `password`
    * Akses: Penuh ke semua fitur, termasuk Manajemen Pengguna.

* **Staf Dinas:**
    * Email: `staf@simapel.com`
    * Password: `password`
    * Akses: Hanya melihat modul proyek (Anda bisa mengubah izin ini melalui akun Super Admin di modul Manajemen Pengguna).

## Fitur yang Akan Dikembangkan di Masa Depan (Opsional)

* **Notifikasi Otomatis**: Pemberitahuan (email/sistem) untuk proyek yang mendekati tenggat waktu atau terlambat.
* **Ekspor ke PDF**: Kemampuan ekspor data proyek ke format PDF.
* **Integrasi Peta Lanjutan**: Menampilkan *popup* peta yang lebih kaya, atau analisis spasial sederhana.
* **Dashboard Kustomisasi**: Memungkinkan admin mengkustomisasi widget di dashboard.
* **Manajemen Pengaturan Sistem**: Mengelola variabel konfigurasi aplikasi melalui antarmuka admin.

## Lisensi

Proyek ini dilisensikan di bawah [Lisensi MIT](https://opensource.org/licenses/MIT). Anda bebas untuk menggunakan, memodifikasi, dan mendistribusikannya.

---
