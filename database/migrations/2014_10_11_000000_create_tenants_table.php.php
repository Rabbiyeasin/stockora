<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            
            // Client ID (6-digit numeric, unique)
            $table->string('client_id', 6)->unique();
            
            // Business Identity
            $table->string('business_name');
            $table->string('slug')->unique();
            $table->string('domain')->nullable()->unique(); // for future custom domains
            
            // Contact Information
            $table->string('email')->unique();
            $table->string('phone', 20);
            $table->string('emergency_contact', 20)->nullable();
            
            // Location Information (NEW)
            $table->string('country', 100)->default('Bangladesh');
            $table->string('region', 100)->nullable(); // State/Division
            $table->string('city', 100)->nullable();
            $table->text('address')->nullable();
            $table->string('postal_code', 20)->nullable();
            
            // Business Type (NEW)
            $table->string('business_type', 50)->nullable(); // retail, wholesale, restaurant, etc.
            
            // Payment Preferences (NEW)
            $table->string('preferred_payment_method', 50)->nullable(); // bkash, nagad, bank, card
            
            // Subscription Status
            $table->enum('status', ['trial', 'active', 'suspended', 'cancelled', 'expired'])->default('trial');
            
            // Trial Period
            $table->timestamp('trial_starts_at')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            
            // Subscription Dates
            $table->timestamp('subscribed_at')->nullable();
            $table->timestamp('subscription_ends_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            
            // Plan Limits
            $table->integer('max_users')->default(5);
            $table->integer('max_stores')->default(1);
            $table->integer('max_products')->default(1000);
            
            // Current Usage (denormalized for quick checks)
            $table->integer('current_users_count')->default(0);
            $table->integer('current_stores_count')->default(0);
            $table->integer('current_products_count')->default(0);
            
            // Settings (JSONB for flexible config)
            $table->json('settings')->nullable();
            $table->json('metadata')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('status');
            $table->index('client_id');
            $table->index(['trial_ends_at', 'status']);
        });
        
        // Auto-generate client_id using PostgreSQL trigger
        DB::statement("
            CREATE OR REPLACE FUNCTION generate_client_id()
            RETURNS TRIGGER AS $$
            DECLARE
                new_client_id TEXT;
                is_unique BOOLEAN;
            BEGIN
                LOOP
                    -- Generate random 6-digit number (100000 to 999999)
                    new_client_id := LPAD(FLOOR(RANDOM() * 900000 + 100000)::TEXT, 6, '0');
                    
                    -- Check uniqueness
                    SELECT NOT EXISTS (
                        SELECT 1 FROM tenants WHERE client_id = new_client_id
                    ) INTO is_unique;
                    
                    EXIT WHEN is_unique;
                END LOOP;
                
                NEW.client_id := new_client_id;
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ");
        
        DB::statement("
            CREATE TRIGGER set_client_id
            BEFORE INSERT ON tenants
            FOR EACH ROW
            WHEN (NEW.client_id IS NULL)
            EXECUTE FUNCTION generate_client_id();
        ");
    }

    public function down(): void
    {
        DB::statement('DROP TRIGGER IF EXISTS set_client_id ON tenants');
        DB::statement('DROP FUNCTION IF EXISTS generate_client_id()');
        Schema::dropIfExists('tenants');
    }
};