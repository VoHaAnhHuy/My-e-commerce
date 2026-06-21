<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * FR-CAT-002: Thêm trạng thái 'archived' vào enum status của products.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE products MODIFY COLUMN status ENUM('active', 'inactive', 'draft', 'archived') DEFAULT 'active'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE products MODIFY COLUMN status ENUM('active', 'inactive', 'draft') DEFAULT 'active'");
    }
};
