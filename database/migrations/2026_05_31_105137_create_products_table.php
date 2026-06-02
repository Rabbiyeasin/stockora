<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->string('sku', 50)->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('buy_price', 12, 2)->default(0);
            $table->decimal('sell_price', 12, 2);
            $table->integer('alert_qty')->default(10);
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'sku']);
            $table->index(['tenant_id', 'store_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};