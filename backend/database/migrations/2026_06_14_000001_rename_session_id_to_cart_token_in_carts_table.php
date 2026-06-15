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
        Schema::table('carts', function (Blueprint $table) {
            $table->renameColumn('session_id', 'cart_token');
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->index('cart_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropIndex(['cart_token']);
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->renameColumn('cart_token', 'session_id');
        });
    }
};
