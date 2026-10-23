<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductAndOrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test product formatting, scope, and database persistence.
     */
    public function test_product_scope_available_and_price_formatting(): void
    {
        // Arrange
        $availableProduct = Product::create([
            'name' => 'Buket Mawar Merah Premium',
            'category' => 'buket',
            'description' => 'Mawar merah segar pilihan 20 tangkai',
            'price' => 350000,
            'image' => 'mawar.jpg',
            'status' => 'available',
        ]);

        $soldOutProduct = Product::create([
            'name' => 'Buket Tulip Putih Langka',
            'category' => 'fresh_flower',
            'description' => 'Tulip impor musim semi',
            'price' => 750000,
            'image' => 'tulip.jpg',
            'status' => 'sold_out',
        ]);

        // Act
        $availableList = Product::available()->get();

        // Assert
        $this->assertCount(1, $availableList);
        $this->assertTrue($availableList->contains($availableProduct));
        $this->assertFalse($availableList->contains($soldOutProduct));
        $this->assertEquals('Rp 350.000', $availableProduct->formatted_price);
        $this->assertEquals('Buket', $availableProduct->category_label);
        $this->assertDatabaseHas('products', [
            'name' => 'Buket Mawar Merah Premium',
            'price' => 350000,
            'status' => 'available',
        ]);
    }

    /**
     * Test order creation and status formatting.
     */
    public function test_order_creation_and_status_formatting(): void
    {
        // Arrange & Act
        $order = Order::create([
            'order_code' => 'FL-20261024-001',
            'customer_name' => 'Siti Rahma',
            'customer_phone' => '081234567890',
            'customer_email' => 'siti@example.com',
            'shipping_address' => 'Jl. Anggrek No. 12',
            'city' => 'Jakarta Selatan',
            'total_amount' => 450000,
            'status' => 'paid',
            'payment_type' => 'qris',
        ]);

        // Assert
        $this->assertEquals('Sudah Dibayar', $order->status_label);
        $this->assertEquals('green', $order->status_color);
        $this->assertEquals('Rp 450.000', $order->formatted_total);
        $this->assertDatabaseHas('orders', [
            'order_code' => 'FL-20261024-001',
            'status' => 'paid',
        ]);
    }
}
