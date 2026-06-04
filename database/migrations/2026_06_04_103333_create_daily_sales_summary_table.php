<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_sales_summary', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->date('date')->index();
            $table->integer('total_sales')->default(0);
            $table->integer('total_items')->default(0);
            $table->decimal('revenue', 12, 2)->default(0);
            $table->decimal('profit', 12, 2)->default(0);
            $table->timestamp('created_at');

            $table->unique(['tenant_id', 'store_id', 'date']);
            $table->index(['tenant_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_sales_summary');
    }
};