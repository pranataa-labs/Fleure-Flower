<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminBackendTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test legacy predictable admin paths return 404 Not Found.
     */
    public function test_legacy_predictable_admin_paths_return_404(): void
    {
        $this->get('/admin')->assertStatus(404);
        $this->get('/admin/login')->assertStatus(404);
        $this->get('/admin/orders')->assertStatus(404);
    }

    /**
     * Test unauthenticated guest cannot access protected admin routes.
     */
    public function test_guest_cannot_access_admin_dashboard_or_orders(): void
    {
        // Act & Assert 1: Dashboard
        $dashboardResponse = $this->get(route('admin.dashboard'));
        $dashboardResponse->assertRedirect(route('admin.login'));
        $dashboardResponse->assertSessionHas('error', 'Silakan login terlebih dahulu.');

        // Act & Assert 2: Orders
        $ordersResponse = $this->get(route('admin.orders.index'));
        $ordersResponse->assertRedirect(route('admin.login'));
    }

    /**
     * Test admin can login with valid credentials.
     */
    public function test_admin_can_login_with_valid_credentials(): void
    {
        // Arrange
        $admin = Admin::create([
            'username' => 'admin_florist',
            'password' => 'secret123',
        ]);

        // Act
        $response = $this->post(route('admin.login.post'), [
            'username' => 'admin_florist',
            'password' => 'secret123',
        ]);

        // Assert
        $response->assertRedirect(route('admin.dashboard'));
        $this->assertTrue(session('admin_logged_in'));
        $this->assertEquals($admin->id, session('admin_id'));
        $this->assertEquals('admin_florist', session('admin_username'));
    }

    /**
     * Test admin login fails with incorrect password.
     */
    public function test_admin_login_fails_with_wrong_password(): void
    {
        // Arrange
        Admin::create([
            'username' => 'superadmin',
            'password' => 'password_asli',
        ]);

        // Act
        $response = $this->from(route('admin.login'))->post(route('admin.login.post'), [
            'username' => 'superadmin',
            'password' => 'password_salah',
        ]);

        // Assert
        $response->assertRedirect(route('admin.login'));
        $response->assertSessionHasErrors(['username']);
        $this->assertFalse(session()->has('admin_logged_in'));
    }

    /**
     * Test authenticated admin can update order status.
     */
    public function test_authenticated_admin_can_update_order_status(): void
    {
        // Arrange
        $admin = Admin::create([
            'username' => 'admin_orders',
            'password' => 'secret123',
        ]);

        $order = Order::create([
            'order_code' => 'FF-TEST-STATUS',
            'customer_name' => 'Budi Santoso',
            'customer_phone' => '08123456789',
            'shipping_address' => 'Jl. Mawar Indah',
            'city' => 'Jakarta Barat',
            'total_amount' => 200000,
            'status' => 'pending',
        ]);

        // Act: Update status order dari 'pending' ke 'processing'
        $response = $this->withSession([
            'admin_logged_in' => true,
            'admin_id' => $admin->id,
            'admin_username' => $admin->username,
        ])->patch(route('admin.orders.status', $order), [
            'status' => 'processing',
        ]);

        // Assert
        $response->assertStatus(302);
        $this->assertEquals('processing', $order->fresh()->status);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'processing',
        ]);
    }

    /**
     * Test admin logout invalidates session.
     */
    public function test_admin_logout_clears_session(): void
    {
        // Arrange & Act
        $response = $this->withSession([
            'admin_logged_in' => true,
            'admin_id' => 1,
            'admin_username' => 'admin_test',
        ])->post(route('admin.logout'));

        // Assert
        $response->assertRedirect(route('admin.login'));
        $this->assertFalse(session()->has('admin_logged_in'));
    }
}
