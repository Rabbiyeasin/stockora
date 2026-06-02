<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null');
            $table->string('invoice_no', 50)->index();
            $table->date('sale_date')->index();
            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('due_amount', 12, 2)->default(0);
            $table->enum('status', ['completed', 'pending', 'cancelled'])->default('completed');
            $table->enum('payment_status', ['paid', 'partial', 'unpaid'])->default('unpaid');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'invoice_no']);
            $table->index(['tenant_id', 'sale_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};