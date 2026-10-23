<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendViewTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test homepage renders hero, brand title, and navigation elements cleanly.
     */
    public function test_homepage_renders_hero_and_navigation_successfully(): void
    {
        // Arrange
        Setting::create([
            'store_name' => 'Fleure Flower Florist',
            'whatsapp' => '08123456789',
            'instagram' => 'fleure.flower',
            'address' => 'Jl. Kaliurang KM 5, Yogyakarta',
        ]);

        $product = Product::create([
            'name' => 'Mawar Putih Elegan',
            'category' => 'buket',
            'description' => 'Mawar putih segar untuk acara wisuda dan pernikahan',
            'price' => 280000,
            'image' => 'white-rose.jpg',
            'status' => 'available',
        ]);

        // Act
        $response = $this->get(route('home'));

        // Assert (Rule 01 & Rule 04: UI Rendering Verification)
        $response->assertStatus(200);
        $response->assertSee('Toko Bunga Premium');
        $response->assertSee('Bunga Indah');
        $response->assertSee('Desain Terbaru');
        $response->assertSee('Mawar Putih Elegan');
        $response->assertSee('Rp 280.000');
        $response->assertSee('Lihat Koleksi');
    }

    /**
     * Test collection page renders category filter buttons and products.
     */
    public function test_collection_page_renders_filter_buttons_and_products(): void
    {
        // Arrange
        $product = Product::create([
            'name' => 'Buket Bunga Matahari Cerah',
            'category' => 'buket',
            'description' => 'Bunga matahari segar 5 tangkai',
            'price' => 195000,
            'image' => 'sunflower.jpg',
            'status' => 'available',
        ]);

        // Act
        $response = $this->get(route('koleksi'));

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Semua Koleksi');
        $response->assertSee('Buket Bunga');
        $response->assertSee('Fresh Flower');
        $response->assertSee('Kartu & Amplop');
        $response->assertSee('Buket Bunga Matahari Cerah');
        $response->assertSee('Rp 195.000');
    }

    /**
     * Test collection page renders clean empty state when no products exist.
     */
    public function test_collection_page_renders_empty_state_when_no_products(): void
    {
        // Act: Kunjungi katalog saat database kosong
        $response = $this->get(route('koleksi'));

        // Assert: Harus menampilkan UI Empty State
        $response->assertStatus(200);
        $response->assertSee('Koleksi Kosong');
        $response->assertSee('Belum ada produk yang tersedia saat ini.');
    }

    /**
     * Test cart page renders empty state illustration and CTA button when cart is empty.
     */
    public function test_cart_page_renders_empty_state_when_cart_is_empty(): void
    {
        // Act: Kunjungi keranjang tanpa session cart
        $response = $this->get(route('cart'));

        // Assert (Rule 01: Empty State UX)
        $response->assertStatus(200);
        $response->assertSee('Keranjang Belanja');
        $response->assertSee('Keranjang Kosong');
        $response->assertSee('Belum ada produk yang ditambahkan ke keranjang.');
        $response->assertSee('Mulai Belanja');
    }

    /**
     * Test cart page renders item list, quantity controls, and order summary when filled.
     */
    public function test_cart_page_renders_items_and_order_summary_when_filled(): void
    {
        // Arrange
        session(['cart' => [
            10 => [
                'id' => 10,
                'name' => 'Buket Peony Pink Romance',
                'category' => 'Buket',
                'price' => 450000,
                'qty' => 2,
                'image' => 'peony.jpg',
            ]
        ]]);

        // Act
        $response = $this->get(route('cart'));

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Buket Peony Pink Romance');
        $response->assertSee('Rp 450.000');
        $response->assertSee('Rp 900.000'); // Subtotal 450.000 * 2
        $response->assertSee('Ringkasan Pesanan');
        $response->assertSee('Kosongkan Keranjang');
    }

    /**
     * Test checkout page renders recipient form fields and shipping requirements.
     */
    public function test_checkout_page_renders_form_fields_and_rules(): void
    {
        // Arrange
        session(['cart' => [
            1 => [
                'id' => 1,
                'name' => 'Buket Mawar Merah',
                'category' => 'Buket',
                'price' => 300000,
                'qty' => 1,
                'image' => 'rose.jpg',
            ]
        ]]);

        // Act
        $response = $this->get(route('checkout'));

        // Assert (Rule 01: Form & Validation Accessibility)
        $response->assertStatus(200);
        $response->assertSee('Data Penerima');
        $response->assertSee('Nama Lengkap *');
        $response->assertSee('Nomor WhatsApp *');
        $response->assertSee('Alamat Pengiriman Lengkap *');
        $response->assertSee('Hanya area dalam Kota Jogja');
        $response->assertSee('Lanjut ke Pembayaran');
    }

    /**
     * Test admin login page renders secure login form.
     */
    public function test_admin_login_page_renders_form(): void
    {
        // Act
        $response = $this->get(route('admin.login'));

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Fleure Flower');
        $response->assertSee('Username');
        $response->assertSee('Password');
        $response->assertSee('Masuk');
    }

    /**
     * Test customer auth pages (login & register) render with social OAuth button.
     */
    public function test_customer_auth_pages_render_cleanly(): void
    {
        // Act 1: Login
        $loginRes = $this->get(route('login'));
        $loginRes->assertStatus(200);
        $loginRes->assertSee('Masuk');
        $loginRes->assertSee('Google');

        // Act 2: Register
        $regRes = $this->get(route('register'));
        $regRes->assertStatus(200);
        $regRes->assertSee('Daftar Akun');
        $regRes->assertSee('Nama Lengkap');
        $regRes->assertSee('Konfirmasi Password');
    }
}
