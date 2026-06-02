<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('code', 20)->index();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone', 20)->index();
            $table->text('address')->nullable();
            $table->string('city', 50)->nullable();
            $table->decimal('total_purchase', 12, 2)->default(0);
            $table->decimal('total_due', 12, 2)->default(0);
            $table->timestamp('last_purchase_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'code']);
            $table->unique(['tenant_id', 'phone']);
            $table->index('tenant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};