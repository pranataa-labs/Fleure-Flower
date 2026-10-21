# ============================================================
# SETUP GITHUB ISSUES - Fleure Flower
# Jalankan script ini di PowerShell setelah login gh auth login
# ============================================================

# Step 1: Login GitHub CLI
Write-Host ">> Langkah 1: Login GitHub CLI" -ForegroundColor Cyan
gh auth login

# Step 2: Buat labels
Write-Host "`n>> Membuat labels..." -ForegroundColor Cyan

gh label create "type: feature" --color "0075ca" --description "Fitur baru" --repo pranataa-labs/Fleure-Flower
gh label create "type: bug" --color "d73a4a" --description "Bug atau error" --repo pranataa-labs/Fleure-Flower
gh label create "type: enhancement" --color "a2eeef" --description "Peningkatan fitur yang ada" --repo pranataa-labs/Fleure-Flower
gh label create "type: chore" --color "e4e669" --description "Task teknis (setup, config)" --repo pranataa-labs/Fleure-Flower
gh label create "priority: high" --color "b60205" --description "Prioritas tinggi" --repo pranataa-labs/Fleure-Flower
gh label create "priority: medium" --color "fbca04" --description "Prioritas sedang" --repo pranataa-labs/Fleure-Flower
gh label create "priority: low" --color "0e8a16" --description "Prioritas rendah" --repo pranataa-labs/Fleure-Flower
gh label create "status: in-progress" --color "e99695" --description "Sedang dikerjakan" --repo pranataa-labs/Fleure-Flower
gh label create "status: ready" --color "c2e0c6" --description "Siap untuk di-merge" --repo pranataa-labs/Fleure-Flower
gh label create "backend" --color "1d2e24" --description "Pekerjaan backend Laravel" --repo pranataa-labs/Fleure-Flower
gh label create "frontend" --color "f4f1ea" --description "Pekerjaan UI/View Blade" --repo pranataa-labs/Fleure-Flower
gh label create "database" --color "5319e7" --description "Migration dan seeder" --repo pranataa-labs/Fleure-Flower
gh label create "payment" --color "006b75" --description "Midtrans payment gateway" --repo pranataa-labs/Fleure-Flower
gh label create "admin" --color "2a4334" --description "Panel admin" --repo pranataa-labs/Fleure-Flower

Write-Host "Labels berhasil dibuat!" -ForegroundColor Green

# Step 3: Buat Issues
Write-Host "`n>> Membuat issues..." -ForegroundColor Cyan

# ISSUE 1
gh issue create `
  --repo pranataa-labs/Fleure-Flower `
  --title "🗄️ [DB] Setup database MySQL & jalankan migration" `
  --label "type: chore,priority: high,database" `
  --body @"
## 📋 Deskripsi
Buat database MySQL lokal dan jalankan semua migration untuk setup awal project.

## ✅ Tasks
- [ ] Buat database \`fleure_flower\` di MySQL/phpMyAdmin
- [ ] Update \`.env\` dengan credential MySQL lokal
- [ ] Jalankan \`php artisan migrate\`
- [ ] Jalankan \`php artisan db:seed\` untuk data awal
- [ ] Verifikasi tabel: \`admins\`, \`products\`, \`orders\`, \`order_items\`, \`settings\`

## 🔧 Command
\`\`\`bash
php artisan migrate
php artisan db:seed
\`\`\`

## ℹ️ Info
- Default admin: **username:** \`admin\` | **password:** \`admin123\`
- Ubah password setelah pertama kali login

## 🔗 Related
Closes saat migration berhasil berjalan.
"@

# ISSUE 2
gh issue create `
  --repo pranataa-labs/Fleure-Flower `
  --title "🔑 [Config] Setup Midtrans Sandbox Keys" `
  --label "type: chore,priority: high,payment" `
  --body @"
## 📋 Deskripsi
Daftarkan akun Midtrans sandbox dan masukkan API keys ke file \`.env\`.

## ✅ Tasks
- [ ] Daftar/login ke [Midtrans Dashboard](https://dashboard.sandbox.midtrans.com/)
- [ ] Buka Settings → Access Keys
- [ ] Salin **Server Key** dan **Client Key**
- [ ] Tambahkan ke \`.env\`:
\`\`\`env
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxxxxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxxxxxx
MIDTRANS_IS_PRODUCTION=false
\`\`\`
- [ ] Test checkout dengan kartu simulasi Midtrans
- [ ] Konfigurasi webhook URL di dashboard Midtrans → \`/midtrans/callback\`

## 🧪 Kartu Test Midtrans
| No. Kartu | CVV | Exp | Result |
|---|---|---|---|
| 5264 2210 3887 4521 | 123 | 01/25 | SUCCESS |
| 5264 2210 3887 4513 | 123 | 01/25 | FAILURE |

## 📎 Referensi
- [Midtrans Sandbox Docs](https://docs.midtrans.com/en/technical-reference/sandbox-test)
"@

# ISSUE 3
gh issue create `
  --repo pranataa-labs/Fleure-Flower `
  --title "🏠 [Frontend] Halaman Beranda — Hero, Desain Terbaru, USP" `
  --label "type: feature,priority: high,frontend" `
  --body @"
## 📋 Deskripsi
Implementasi halaman beranda (homepage) dengan desain editorial premium florist.

## ✅ Tasks
- [x] Hero section — full viewport dengan overlay gradient
- [x] Section Desain Terbaru — 4 card grid dari produk terbaru
- [x] USP Bar — 4 kolom keunggulan toko
- [x] Koleksi Pilihan — 4 produk random
- [x] Section Tentang Kami dengan statistik
- [x] Section Kontak & Google Maps
- [x] CTA Banner WhatsApp

## 🎨 Design System
- Font: Playfair Display + Montserrat
- Primary: \`#1d2e24\` (dark green)
- Background: \`#F4F1EA\` (cream)
- Bahasa: Indonesia penuh
- Icons: Phosphor Icons

## 📸 Screenshot
_Tambahkan screenshot setelah halaman berjalan._
"@

# ISSUE 4
gh issue create `
  --repo pranataa-labs/Fleure-Flower `
  --title "🌸 [Frontend] Halaman Koleksi dengan Filter Kategori" `
  --label "type: feature,priority: high,frontend" `
  --body @"
## 📋 Deskripsi
Halaman koleksi menampilkan semua produk tersedia dengan filter kategori real-time (tanpa reload halaman).

## ✅ Tasks
- [x] Header banner section
- [x] Filter buttons: Semua / Buket / Fresh Flower / Amplop
- [x] Grid produk 2-4 kolom responsif
- [x] Filter via JS \`data-category\` attribute
- [x] URL parameter \`?filter=buket\` untuk deep-link
- [x] Empty state jika kategori kosong
- [x] Hover reveal tombol Beli & Pesan

## 🔗 URL
\`/koleksi\` — \`/koleksi?filter=buket\`
"@

# ISSUE 5
gh issue create `
  --repo pranataa-labs/Fleure-Flower `
  --title "🛒 [Feature] Keranjang Belanja (Cart)" `
  --label "type: feature,priority: high,backend,frontend" `
  --body @"
## 📋 Deskripsi
Implementasi fitur keranjang belanja berbasis session Laravel.

## ✅ Tasks
- [x] \`CartController\` — add, update quantity, remove, clear
- [x] Halaman \`/cart\` — daftar item dengan update qty & hapus
- [x] Session-based storage (tidak perlu login customer)
- [x] Cart badge di navbar (jumlah item)
- [x] Tombol "Tambah ke Keranjang" di halaman produk
- [ ] Test flow: tambah produk → lihat cart → update qty → hapus

## 🔗 Routes
\`\`\`
GET  /cart
POST /cart/add/{product}
PATCH /cart/update/{id}
DELETE /cart/remove/{id}
DELETE /cart/clear
\`\`\`
"@

# ISSUE 6
gh issue create `
  --repo pranataa-labs/Fleure-Flower `
  --title "💳 [Feature] Checkout & Integrasi Midtrans Snap" `
  --label "type: feature,priority: high,backend,payment" `
  --body @"
## 📋 Deskripsi
Proses checkout dari keranjang ke pembayaran via Midtrans Snap.js.

## ✅ Tasks
- [x] Halaman checkout dengan form data pelanggan
- [x] Simpan order ke tabel \`orders\` dan \`order_items\`
- [x] Generate Midtrans Snap Token
- [x] Halaman payment dengan tombol \`snap.pay()\`
- [x] Webhook handler \`POST /midtrans/callback\`
- [x] Verifikasi signature key dari Midtrans
- [x] Update status order setelah pembayaran
- [x] Halaman sukses \`/order/success/{code}\`
- [ ] Test end-to-end di sandbox Midtrans

## 🔗 Routes
\`\`\`
GET  /checkout
POST /checkout
POST /midtrans/callback  (webhook, CSRF excluded)
GET  /order/success/{code}
\`\`\`
"@

# ISSUE 7
gh issue create `
  --repo pranataa-labs/Fleure-Flower `
  --title "🛠️ [Admin] Panel Admin — Dashboard, CRUD Produk, Pesanan" `
  --label "type: feature,priority: high,admin,backend" `
  --body @"
## 📋 Deskripsi
Panel admin untuk mengelola produk, pesanan, dan pengaturan toko.

## ✅ Tasks
### Auth
- [x] Login admin dengan session guard custom
- [x] Middleware \`AdminAuthenticated\`
- [x] Logout + session invalidate

### Dashboard
- [x] 6 kartu statistik (buket, fresh flower, amplop, sold out, total pesanan, pesanan dibayar)
- [x] Tabel 5 pesanan terbaru

### Produk
- [x] Daftar produk dengan filter search/kategori/status
- [x] Tambah produk + upload foto (maks 3MB)
- [x] Edit produk + ganti foto opsional
- [x] Hapus produk + hapus foto dari storage
- [x] Toggle status: Tersedia / Sold Out

### Pesanan
- [x] Daftar semua pesanan dengan filter status
- [x] Detail pesanan (info pelanggan, item, status)
- [x] Update status pesanan

### Pengaturan
- [x] Nama Toko, WhatsApp, Instagram, Alamat, Google Maps Embed

## 🔐 Login Default
- Username: \`admin\`
- Password: \`admin123\`
"@

# ISSUE 8
gh issue create `
  --repo pranataa-labs/Fleure-Flower `
  --title "📱 [Frontend] Responsif Mobile & Tablet — Semua Halaman" `
  --label "type: enhancement,priority: medium,frontend" `
  --body @"
## 📋 Deskripsi
Pastikan semua halaman tampil dengan baik di mobile, tablet, dan desktop.

## ✅ Breakpoints
| Breakpoint | Ukuran |
|---|---|
| Mobile | < 640px |
| Tablet | 640px – 1024px |
| Desktop | > 1024px |

## ✅ Halaman yang perlu dicek
- [ ] Beranda — hero, grid desain terbaru, USP bar
- [ ] Koleksi — filter buttons, grid 2-4 kolom
- [ ] Keranjang — layout item + summary
- [ ] Checkout — form + order summary
- [ ] Admin panel — sidebar collapse di mobile

## 🧪 Tools
- Chrome DevTools → Device Mode
- Responsively App (opsional)
"@

# ISSUE 9
gh issue create `
  --repo pranataa-labs/Fleure-Flower `
  --title "🔒 [Security] Hardening Admin & Input Validation" `
  --label "type: enhancement,priority: medium,backend,admin" `
  --body @"
## 📋 Deskripsi
Memastikan keamanan aplikasi dari sisi input validation, XSS, dan CSRF.

## ✅ Tasks
- [ ] Ubah password admin default \`admin123\` setelah deploy
- [ ] Rate limiting pada route \`/admin/login\`
- [ ] Validasi MIME type file upload (tidak hanya ekstensi)
- [ ] Pastikan semua form pakai \`@csrf\`
- [ ] \`htmlspecialchars\` / \`{{ }}\ ` pada semua output Blade
- [ ] Webhook Midtrans verifikasi signature key ✅ (sudah ada)
- [ ] Tambahkan \`APP_DEBUG=false\` saat production

## 🛡️ Referensi
- [Laravel Security Best Practices](https://laravel.com/docs/security)
"@

# ISSUE 10
gh issue create `
  --repo pranataa-labs/Fleure-Flower `
  --title "🚀 [Deployment] Deploy ke Production Server" `
  --label "type: chore,priority: low,backend" `
  --body @"
## 📋 Deskripsi
Checklist deployment Fleure Flower ke production server.

## ✅ Pre-deployment
- [ ] Set \`APP_ENV=production\` dan \`APP_DEBUG=false\` di \`.env\`
- [ ] Set \`MIDTRANS_IS_PRODUCTION=true\` dan ganti ke production keys
- [ ] Jalankan \`php artisan optimize\`
- [ ] Jalankan \`php artisan migrate --force\`
- [ ] Jalankan \`php artisan storage:link\`
- [ ] Set permission folder \`storage/\` dan \`bootstrap/cache/\`
- [ ] Pastikan \`APP_URL\` sesuai dengan domain produksi
- [ ] Setup webhook Midtrans production ke \`https://domain.com/midtrans/callback\`

## ✅ Server Requirements
- PHP >= 8.2
- MySQL >= 8.0
- Composer
- mod_rewrite (Apache) atau config Nginx

## 📋 Nginx Config
\`\`\`nginx
root /path/to/project/public;
index index.php;
location / { try_files \$uri \$uri/ /index.php?\$query_string; }
\`\`\`
"@

Write-Host "`n✅ Semua 10 issues berhasil dibuat!" -ForegroundColor Green
Write-Host "📎 Lihat di: https://github.com/pranataa-labs/Fleure-Flower/issues" -ForegroundColor Cyan
