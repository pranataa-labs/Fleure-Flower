# 🌸 Fleure Flower - Premium Florist E-Commerce

Fleure Flower adalah aplikasi e-Commerce toko bunga premium yang dibangun menggunakan framework **Laravel 13** dan **Tailwind CSS v4**. Aplikasi ini dirancang untuk memberikan pengalaman berbelanja bunga (buket, fresh flower, dan kartu ucapan) yang elegan, responsif, dan aman, lengkap dengan **Admin Management Suite** berbasis **Shadcn Admin Design System** yang mendukung mode Light, Dark, dan System.

---

## ✨ Fitur Utama

### 🛒 Fitur Pelanggan (Storefront)
*   **Autentikasi Modern:** Mendukung pendaftaran dan login standar (Email/Password) serta **Login via Google** (OAuth 2.0 menggunakan Laravel Socialite).
*   **Manajemen Profil:** Pengguna dapat memperbarui nama (dengan batasan 24 jam), bio profil, serta memilih avatar kustom dari *icon library* bawaan atau menggunakan foto profil Google.
*   **Katalog Interaktif:** Menampilkan koleksi bunga dengan filter berdasarkan kategori (*Buket, Fresh Flower, Amplop/Kartu*).
*   **Keranjang Belanja (Cart):** Penyimpanan keranjang belanja terintegrasi dengan *session* untuk kemudahan *checkout*.
*   **Checkout & Payment Gateway:** Integrasi **Midtrans Snap API** memungkinkan pelanggan melakukan pembayaran dengan berbagai metode (GoPay, QRIS, Virtual Account Bank, Kartu Kredit, dll) secara *real-time*.

### 📊 Fitur Admin Panel (Shadcn Admin UI)
*   **Shadcn Design System & Dark Mode:** Tampilan antarmuka modern dengan palet warna Slate OKLCH, radius 0.625rem, collapsible sidebar 16rem, sticky blurred header, serta **3-way Theme Toggle** (Light, Dark, dan System) dengan script anti-FOUC.
*   **Dashboard Analitik:** 4 KPI Cards ringkas dengan tren persentase, tabel 5 pesanan terbaru, dan **Grafik Pendapatan 7 Hari Terakhir** (menggunakan *Chart.js*) yang otomatis menyesuaikan tema (Light/Dark) secara instan.
*   **Manajemen Produk (CRUD):** Tambah, edit, hapus katalog, dan filter status produk (Tersedia / Sold Out) dengan area *file upload dropzone* dan *live image preview*.
*   **Manajemen Pesanan:** Memantau pesanan masuk, melacak status pembayaran Midtrans, dan memperbarui alur proses pengiriman.
*   **Pengaturan Toko:** Mengelola nama toko, alamat atelier, kontak WhatsApp, dan jam operasional.
*   **Command Palette (`⌘K` / `Ctrl+K`):** Akses cepat antar halaman admin langsung melalui keyboard.

### 🛡️ Keamanan & Standar Teknis
*   **Proteksi Brute-Force (Rate Limiting):** Membatasi jumlah percobaan *login* yang gagal berturut-turut pada akun Pelanggan maupun Admin.
*   **Database Race-Condition Lock (Pessimistic Locking):** Menggunakan `DB::transaction()` dan `lockForUpdate()` pada *webhook/callback* Midtrans untuk mencegah pemrosesan transaksi ganda.
*   **XSS & CSRF Protection:** Seluruh form dilindungi token `@csrf`. Input formulir disanitasi menggunakan `strip_tags()` untuk mencegah injeksi skrip HTML/JS berbahaya.
*   **Graceful Error Handling:** Penanganan *try-catch* pada integrasi API pihak ketiga (Midtrans) agar kegagalan gateway tidak merusak pengalaman pengguna.
*   **Strict Unique ID Generation:** Pembuatan kode faktur / Order ID unik dengan verifikasi bertahap.

---

## 🚀 Panduan Instalasi (Development)

1. **Clone Repository:**
   ```bash
   git clone https://github.com/pranataa-labs/Fleure-Flower.git
   cd Fleure-Flower
   ```

2. **Install Dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup:**
   Salin file konfigurasi lingkungan:
   ```bash
   cp .env.example .env
   ```
   *Pastikan kredensial database MySQL, Midtrans Server Key, dan Google OAuth Client ID telah disesuaikan.*

4. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

5. **Migrasi Database & Seeder:**
   ```bash
   php artisan migrate --seed
   ```
   *Seeder bawaan menyediakan akun admin default (`username: admin`, `password: admin123`) dan katalog bunga awal.*

6. **Storage Link:**
   ```bash
   php artisan storage:link
   ```

7. **Kompilasi Asset & Jalankan Aplikasi:**
   ```bash
   # Jalankan Vite server (port 5173 / 5174)
   npm run dev

   # Di terminal terpisah, jalankan server Laravel
   php artisan serve
   ```
   - **Storefront Pelanggan:** `http://localhost:8000`
   - **Admin Management Suite:** `http://localhost:8000/admin`

---

## 🛠️ Tech Stack

*   **Backend:** [Laravel 13](https://laravel.com/) (PHP 8.3+)
*   **Admin UI & Theme:** [Tailwind CSS v4](https://tailwindcss.com/), [Shadcn Admin UI Tokens](https://ui.shadcn.com/), [Lucide Icons](https://lucide.dev/), [Chart.js](https://www.chartjs.org/)
*   **Storefront UI:** Blade Templates, Tailwind CSS, Alpine.js, Phosphor Icons
*   **Asset Bundler:** [Vite v8](https://vite.dev/) (`@tailwindcss/vite`, `laravel-vite-plugin`)
*   **Database:** MySQL (MariaDB compatible)
*   **Payment Gateway:** Midtrans Snap API (`midtrans/midtrans-php`)
*   **OAuth Provider:** Google Socialite (`laravel/socialite`)

---

*Dikembangkan oleh [pranataa-labs](https://github.com/pranataa-labs).*
