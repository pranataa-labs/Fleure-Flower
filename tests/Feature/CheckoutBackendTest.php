<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutBackendTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test checkout page redirects to cart if cart session is empty.
     */
    public function test_checkout_page_redirects_when_cart_is_empty(): void
    {
        // Act: Kunjungi checkout tanpa isi keranjang
        $response = $this->get(route('checkout'));

        // Assert: Redirect ke halaman cart dengan pesan error
        $response->assertRedirect(route('cart'));
        $response->assertSessionHas('error', 'Keranjang Anda masih kosong.');
    }

    /**
     * Test checkout process validation enforces mandatory fields.
     */
    public function test_checkout_process_validates_required_fields(): void
    {
        // Arrange: Siapkan keranjang
        session(['cart' => [
            1 => [
                'id' => 1,
                'name' => 'Buket Mawar',
                'price' => 150000,
                'qty' => 1,
            ]
        ]]);

        // Act: Submit data kosong
        $response = $this->post(route('checkout.process'), []);

        // Assert: Validasi error pada field wajib
        $response->assertSessionHasErrors([
            'customer_name',
            'customer_phone',
            'shipping_address',
            'city',
        ]);
        $this->assertEquals(0, Order::count());
    }

    /**
     * Test midtrans webhook callback rejects invalid or forged signature with 400 or 403.
     */
    public function test_midtrans_webhook_rejects_invalid_notification_payload(): void
    {
        // Act: Panggil endpoint callback Midtrans dengan payload kosong/palsu
        $response = $this->postJson(route('midtrans.callback'), [
            'order_id' => 'FF-FAKE123',
            'status_code' => '200',
            'gross_amount' => '150000',
            'signature_key' => 'invalidsignature123',
        ]);

        // Assert: Harus ditolak dengan status HTTP 400 (Invalid notification payload)
        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'Invalid notification payload',
        ]);
    }

    /**
     * Test order success page displays order details and items.
     */
    public function test_order_success_page_displays_order(): void
    {
        // Arrange
        $order = Order::create([
            'order_code' => 'FF-SUCCESS01',
            'customer_name' => 'Dewi Lestari',
            'customer_phone' => '081299998888',
            'customer_email' => 'dewi@example.com',
            'shipping_address' => 'Jl. Flamboyan No. 5',
            'city' => 'Bandung',
            'total_amount' => 350000,
            'status' => 'paid',
        ]);

        // Act
        $response = $this->get(route('order.success', $order->order_code));

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Dewi Lestari');
        $response->assertSee('FF-SUCCESS01');
    }
}
