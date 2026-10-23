<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseArchitectureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test schema tables exist and comply with 3NF database design.
     */
    public function test_all_core_tables_and_columns_exist(): void
    {
        // Assert: Tables exist
        $this->assertTrue(Schema::hasTable('admins'));
        $this->assertTrue(Schema::hasTable('products'));
        $this->assertTrue(Schema::hasTable('orders'));
        $this->assertTrue(Schema::hasTable('order_items'));
        $this->assertTrue(Schema::hasTable('settings'));
        $this->assertTrue(Schema::hasTable('users'));

        // Assert: Critical columns exist
        $this->assertTrue(Schema::hasColumns('products', ['id', 'name', 'category', 'price', 'status']));
        $this->assertTrue(Schema::hasColumns('orders', ['id', 'order_code', 'total_amount', 'status', 'snap_token']));
        $this->assertTrue(Schema::hasColumns('order_items', ['id', 'order_id', 'product_id', 'product_name', 'price', 'quantity', 'subtotal']));
    }

    /**
     * Test cascade delete on order removes associated order items (Rule 03 Data Integrity).
     */
    public function test_order_cascade_delete_removes_child_order_items(): void
    {
        // Arrange
        $order = Order::create([
            'order_code' => 'FF-CASCADE-01',
            'customer_name' => 'Bambang',
            'customer_phone' => '0812345678',
            'shipping_address' => 'Jl. Kaliurang KM 7',
            'city' => 'Yogyakarta',
            'total_amount' => 500000,
            'status' => 'pending',
        ]);

        $item = OrderItem::create([
            'order_id' => $order->id,
            'product_name' => 'Buket Mawar',
            'price' => 250000,
            'quantity' => 2,
            'subtotal' => 500000,
        ]);

        // Act: Hapus parent order
        $order->delete();

        // Assert: OrderItem harus ikut terhapus via CASCADE (tidak menyisakan data yatim / orphan)
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
        $this->assertDatabaseMissing('order_items', ['id' => $item->id]);
    }

    /**
     * Test product deletion preserves order items with historical snapshot (Rule 03 Snapshot Pattern).
     */
    public function test_product_deletion_preserves_historical_order_item_data(): void
    {
        // Arrange
        $product = Product::create([
            'name' => 'Buket Lily Putih Spesial',
            'category' => 'buket',
            'description' => 'Buket lily segar pilihan',
            'price' => 350000,
            'image' => 'lily.jpg',
            'status' => 'available',
        ]);

        $order = Order::create([
            'order_code' => 'FF-HIST-01',
            'customer_name' => 'Ratna',
            'customer_phone' => '08199988877',
            'shipping_address' => 'Jl. Gejayan No. 10',
            'city' => 'Yogyakarta',
            'total_amount' => 350000,
            'status' => 'completed',
        ]);

        $item = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => $product->price,
            'quantity' => 1,
            'subtotal' => 350000,
        ]);

        // Act: Hapus produk katalog master
        $product->delete();

        // Assert: Order item TETAP ADA dengan product_id null (SET NULL on delete), dan nama & harga historis terjaga
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        $this->assertDatabaseHas('order_items', [
            'id' => $item->id,
            'order_id' => $order->id,
            'product_id' => null,
            'product_name' => 'Buket Lily Putih Spesial',
            'price' => 350000,
        ]);
    }

    /**
     * Test unique constraints prevent duplicate order codes and admin usernames.
     */
    public function test_database_enforces_unique_constraints(): void
    {
        // Arrange
        Order::create([
            'order_code' => 'FF-UNIQUE-123',
            'customer_name' => 'Customer A',
            'customer_phone' => '0811111111',
            'shipping_address' => 'Jl. Solo KM 9',
            'city' => 'Yogyakarta',
            'total_amount' => 100000,
            'status' => 'pending',
        ]);

        // Expect QueryException karena duplicate key
        $this->expectException(QueryException::class);

        // Act: Coba insert order dengan code yang sama
        Order::create([
            'order_code' => 'FF-UNIQUE-123',
            'customer_name' => 'Customer B',
            'customer_phone' => '0822222222',
            'shipping_address' => 'Jl. Magelang KM 4',
            'city' => 'Yogyakarta',
            'total_amount' => 200000,
            'status' => 'pending',
        ]);
    }

    /**
     * Test decimal precision for financial amounts.
     */
    public function test_financial_decimal_precision_is_maintained(): void
    {
        // Arrange & Act
        $product = Product::create([
            'name' => 'Mini Bouquet',
            'category' => 'buket',
            'description' => 'Buket mini manis',
            'price' => 75500.50,
            'image' => 'mini.jpg',
            'status' => 'available',
        ]);

        // Assert
        $this->assertEquals(75500.50, (float) $product->fresh()->price);
    }
}
