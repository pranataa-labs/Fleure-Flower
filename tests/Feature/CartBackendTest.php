<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartBackendTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test adding available product to cart updates session and returns success JSON.
     */
    public function test_can_add_available_product_to_cart_via_json(): void
    {
        // Arrange
        $product = Product::create([
            'name' => 'Buket Mawar Pink',
            'category' => 'buket',
            'description' => 'Mawar pink segar harum',
            'price' => 250000,
            'image' => 'pink-rose.jpg',
            'status' => 'available',
        ]);

        // Act
        $response = $this->postJson(route('cart.add', $product));

        // Assert
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'cartCount' => 1,
        ]);
        $this->assertEquals(1, session('cart')[$product->id]['qty']);
        $this->assertEquals(250000, session('cart')[$product->id]['price']);
    }

    /**
     * Test cannot add sold out product to cart and returns 400 error.
     */
    public function test_cannot_add_unavailable_product_to_cart(): void
    {
        // Arrange
        $product = Product::create([
            'name' => 'Buket Anggrek Langka',
            'category' => 'fresh_flower',
            'description' => 'Anggrek ungu pilihan',
            'price' => 500000,
            'image' => 'anggrek.jpg',
            'status' => 'sold_out',
        ]);

        // Act
        $response = $this->postJson(route('cart.add', $product));

        // Assert
        $response->assertStatus(400);
        $response->assertJson([
            'error' => 'Produk ini sudah tidak tersedia.',
        ]);
        $this->assertNull(session('cart'));
    }

    /**
     * Test updating product quantity in cart.
     */
    public function test_can_update_cart_item_quantity(): void
    {
        // Arrange: Inisialisasi cart di session
        session(['cart' => [
            1 => [
                'id' => 1,
                'name' => 'Buket Lily',
                'price' => 300000,
                'qty' => 1,
            ]
        ]]);

        // Act: Update quantity menjadi 3
        $response = $this->patch(route('cart.update', 1), ['qty' => 3]);

        // Assert
        $response->assertStatus(302);
        $this->assertEquals(3, session('cart')[1]['qty']);
    }

    /**
     * Test removing product and clearing cart completely.
     */
    public function test_can_remove_item_and_clear_entire_cart(): void
    {
        // Arrange
        session(['cart' => [
            1 => ['id' => 1, 'name' => 'Buket A', 'price' => 100000, 'qty' => 1],
            2 => ['id' => 2, 'name' => 'Buket B', 'price' => 200000, 'qty' => 1],
        ]]);

        // Act 1: Hapus item 1
        $this->delete(route('cart.remove', 1));

        // Assert 1
        $this->assertArrayNotHasKey(1, session('cart'));
        $this->assertArrayHasKey(2, session('cart'));

        // Act 2: Kosongkan seluruh keranjang
        $this->delete(route('cart.clear'));

        // Assert 2
        $this->assertNull(session('cart'));
    }
}
