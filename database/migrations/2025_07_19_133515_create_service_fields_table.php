<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('service_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->string('field_name', 150);
            $table->string('field_code', 50)->comment('Unique identifier for the field');
            $table->text('description')->nullable();
            $table->decimal('base_price', 10, 2)->default(0.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique('field_code');
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_fields');
    }
};