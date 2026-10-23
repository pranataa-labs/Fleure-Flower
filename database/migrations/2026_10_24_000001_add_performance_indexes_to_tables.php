<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Mengoptimalkan performa query berdasarkan pola akses aplikasi (Rule 03 Database).
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->index(['status', 'category'], 'idx_products_status_category');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index('status', 'idx_orders_status');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('google_id', 'idx_users_google_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_products_status_category');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('idx_orders_status');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_google_id');
        });
    }
};
