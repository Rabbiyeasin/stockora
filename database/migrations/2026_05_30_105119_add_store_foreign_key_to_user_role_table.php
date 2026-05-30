<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
    Schema::table('user_role', function (Blueprint $table) {
        $table->foreign('store_id')
              ->references('id')
              ->on('stores')
              ->cascadeOnDelete();
    });
    }

    public function down(): void
    {
        Schema::table('user_role', function (Blueprint $table) {
            $table->dropForeign(['store_id']);
        });
    }
};
