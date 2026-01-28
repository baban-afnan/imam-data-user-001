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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['email', 'announcement'])->default('email');
            $table->enum('recipient_type', ['all', 'role', 'single', 'manual_email', 'none'])->nullable();
            $table->string('recipient_data')->nullable()->comment('Stores role name, user_id, email, etc.');
            $table->string('subject')->nullable();
            $table->text('message');
            $table->string('attachment')->nullable();
            $table->boolean('is_active')->default(true);
            $table->enum('status', ['sent', 'active', 'inactive'])->default('sent');
            $table->string('performed_by')->nullable()->comment('User full name');
            $table->string('approved_by')->nullable(); // Kept from user request just in case
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
