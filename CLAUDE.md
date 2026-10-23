# Fleure Flower — Premium Florist e-Commerce (Laravel + Tailwind)

**Tech Stack:** Laravel, Tailwind CSS, Blade, MySQL, Midtrans Payment Gateway, Google OAuth.  
**Standards & Operating Workflow:** Terintegrasi penuh dengan [Workflow Master](.workflow/README.md).

---

## 1. Operating Discipline & Engineering Workflow
Semua pekerjaan rekayasa pada repositori ini WAJIB mematuhi 9 tahapan alur kerja di `.workflow/workflows/00-engineering-workflow-ai-coding.md`:
1. **Triage:** Pahami tujuan, klasifikasikan tipe tugas dan tingkat risiko (Low / Medium / High).
2. **Discover:** Periksa file terkait, model, migrasi, dan kontrak data sebelum melakukan perubahan.
3. **Design:** Rancang acceptance criteria, flow data, dan boundary error.
4. **Plan:** Tentukan file dan urutan modifikasi; hindari overengineering.
5. **Implement:** Tulis kode bersih, aman, dan modular sesuai konvensi Laravel.
6. **Verify:** Jalankan test suite secara nyata (`php artisan test` atau `./vendor/bin/phpunit`).
7. **Review:** Periksa diff, otorisasi, validasi transaksi payment gateway, dan integritas data.
8. **Release:** Verifikasi migrasi skema dan kesehatan aplikasi.
9. **Report:** Laporkan hasil verifikasi nyata dengan jujur (Passed / Failed / Skipped).

---

## 2. Aturan Domain Khusus Fleure Flower
- **Integritas Payment & Checkout:** Integrasi Midtrans WAJIB memverifikasi signature key pada webhook notification di sisi server. Jangan percaya status pembayaran dari query parameter client tanpa verifikasi webhook/API Midtrans.
- **Validasi Harga & Stok:** Harga produk, ongkos kirim, dan ketersediaan stok bunga WAJIB divalidasi ulang di backend saat checkout.
- **Testing (Pola AAA):** Pengujian automated wajib menggunakan SQLite in-memory (`RefreshDatabase`), mengikuti standar `.workflow/rules/04-testing-qa-rules.md`.
