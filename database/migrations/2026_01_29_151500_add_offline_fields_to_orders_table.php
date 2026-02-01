<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add customer_user_id (nullable FK to users)
            $table->foreignId('customer_user_id')
                ->nullable()
                ->after('user_id')
                ->constrained('users')
                ->nullOnDelete();
            
            // Add order_source enum with default 'online'
            $table->enum('order_source', ['online', 'offline'])
                ->default('online')
                ->after('customer_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['customer_user_id']);
            $table->dropColumn(['customer_user_id', 'order_source']);
        });
    }
};
