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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')->nullable()->constrained()->onDelete('set null')->comment('產品分類');
            $table->string('product_name')->comment('產品名稱');
            $table->text('product_description')->comment('產品描述');
            $table->unsignedInteger('price')->comment('產品價錢');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
