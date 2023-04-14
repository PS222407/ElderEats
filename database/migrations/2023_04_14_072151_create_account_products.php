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
        Schema::create('account_products', function (Blueprint $table) {
            $table->foreignId('account_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->date('expiration_date');
            $table->timestamp('ran_out_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_products');
    }
};
