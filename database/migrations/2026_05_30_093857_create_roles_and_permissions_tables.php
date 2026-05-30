<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Roles table
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            
            $table->string('name', 100);
            $table->string('slug', 100);
            $table->text('description')->nullable();
            
            $table->boolean('is_system_role')->default(false); // prevents deletion
            $table->integer('level')->default(0); // hierarchy level
            
            $table->timestamps();
            
            $table->unique(['tenant_id', 'slug']);
        });
        
        // Permissions table (global, not tenant-specific)
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->text('description')->nullable();
            $table->string('module', 50)->nullable(); // products, sales, users, etc.
            
            $table->timestamps();
        });
        
        // Role-Permission pivot
        Schema::create('role_permission', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            
            $table->timestamp('created_at')->useCurrent();
            
            $table->unique(['role_id', 'permission_id']);
        });
        
        // User-Role pivot
        Schema::create('user_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->foreignId('store_id')->nullable()->constrained()->onDelete('cascade');
            
            $table->timestamp('assigned_at')->useCurrent();
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->unique(['user_id', 'role_id', 'store_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_role');
        Schema::dropIfExists('role_permission');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};