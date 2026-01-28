<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();

            // Foreign key to users table
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('balance', 12, 2)->default(0.00);
            $table->decimal('hold_amount', 12, 2)->default(0.00);
            $table->decimal('available_balance', 12, 2)->default(0.00);
            $table->string('wallet_number', 10);
            $table->string('currency', 3)->default('NGN');
            $table->decimal('bonus', 12, 2)->default(0.00);
            $table->enum('status', ['active', 'inactive', 'suspended', 'closed'])->default('active');
            $table->timestamp('last_activity')->nullable();
            $table->timestamps(); 
            
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
