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
        Schema::dropIfExists('sme_datas');
        Schema::create('sme_datas', function (Blueprint $table) {
            $table->id();
            $table->string('data_id');
            $table->string('network');
            $table->string('plan_type');
            $table->string('amount');
            $table->string('size');
            $table->string('validity');
            $table->enum('status', ['enabled', 'disabled'])->default('enabled');
            $table->timestamps();
        });

        // Insert sample data provided by the user
        $data = [
            ['data_id' => '183', 'network' => '9MOBILE', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '300', 'size' => '1.0GB', 'validity' => '30 days'],
            ['data_id' => '184', 'network' => '9MOBILE', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '440', 'size' => '1.5GB', 'validity' => '30 days'],
            ['data_id' => '185', 'network' => '9MOBILE', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '580', 'size' => '2.0GB', 'validity' => '30 days'],
            ['data_id' => '186', 'network' => '9MOBILE', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '860', 'size' => '3.0GB', 'validity' => '30 days'],
            ['data_id' => '188', 'network' => '9MOBILE', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '1450', 'size' => '5.0GB', 'validity' => '30 days'],
            ['data_id' => '189', 'network' => '9MOBILE', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '2900', 'size' => '10.0GB', 'validity' => '30 days'],
            ['data_id' => '221', 'network' => '9MOBILE', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '150', 'size' => '500.0MB', 'validity' => '30 days'],
            ['data_id' => '229', 'network' => '9MOBILE', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '5700', 'size' => '20.0GB', 'validity' => 'Monthly'],
            ['data_id' => '265', 'network' => '9MOBILE', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '1220', 'size' => '4.0GB', 'validity' => '30 day'],
            ['data_id' => '145', 'network' => 'AIRTEL', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '950', 'size' => '1.0GB', 'validity' => '30 days'],
            ['data_id' => '147', 'network' => 'AIRTEL', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '4750', 'size' => '5.0GB', 'validity' => '30 days'],
            ['data_id' => '148', 'network' => 'AIRTEL', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '9500', 'size' => '10.0GB', 'validity' => '30 days'],
            ['data_id' => '165', 'network' => 'AIRTEL', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '500', 'size' => '500.0MB', 'validity' => '30 days'],
            ['data_id' => '193', 'network' => 'AIRTEL', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '300', 'size' => '300.0MB', 'validity' => '14 days'],
            ['data_id' => '226', 'network' => 'AIRTEL', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '14250', 'size' => '15.0GB', 'validity' => '30 days'],
            ['data_id' => '194', 'network' => 'GLO', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '440', 'size' => '1.0GB', 'validity' => '30days'],
            ['data_id' => '195', 'network' => 'GLO', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '880', 'size' => '2.0GB', 'validity' => '30days'],
            ['data_id' => '196', 'network' => 'GLO', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '1320', 'size' => '3.0GB', 'validity' => '30days'],
            ['data_id' => '197', 'network' => 'GLO', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '2200', 'size' => '5.0GB', 'validity' => '30days'],
            ['data_id' => '200', 'network' => 'GLO', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '4400', 'size' => '10.0GB', 'validity' => '30days'],
            ['data_id' => '203', 'network' => 'GLO', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '220', 'size' => '500.0MB', 'validity' => '30 days'],
            ['data_id' => '225', 'network' => 'GLO', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '100', 'size' => '200.0MB', 'validity' => '14 days'],
            ['data_id' => '227', 'network' => 'AIRTEL', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '19000', 'size' => '20.0GB', 'validity' => '30 days'],
            ['data_id' => '283', 'network' => 'AIRTEL', 'plan_type' => 'SME', 'amount' => '3500', 'size' => '10.0GB', 'validity' => 'Monthly'],
            ['data_id' => '299', 'network' => 'AIRTEL', 'plan_type' => 'SME', 'amount' => '45', 'size' => '75.0MB', 'validity' => '7 days'],
            ['data_id' => '301', 'network' => 'AIRTEL', 'plan_type' => 'SME', 'amount' => '200', 'size' => '500.0MB', 'validity' => '7 days'],
            ['data_id' => '304', 'network' => 'AIRTEL', 'plan_type' => 'SME', 'amount' => '2450', 'size' => '7.0GB', 'validity' => '7 days'],
            ['data_id' => '308', 'network' => 'AIRTEL', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '100', 'size' => '100.0 MB', 'validity' => '7 days'],
            ['data_id' => '314', 'network' => 'AIRTEL', 'plan_type' => 'CORPORATE GIFTING', 'amount' => '1900', 'size' => '2.0 GB', 'validity' => '30 days'],
            ['data_id' => '310', 'network' => 'AIRTEL', 'plan_type' => 'SME', 'amount' => '100', 'size' => '150.0 MB', 'validity' => '1 day'],
            ['data_id' => '311', 'network' => 'AIRTEL', 'plan_type' => 'SME', 'amount' => '200', 'size' => '300.0MB', 'validity' => '2 days'],
            ['data_id' => '312', 'network' => 'AIRTEL', 'plan_type' => 'SME', 'amount' => '300', 'size' => '600.0MB', 'validity' => '2 days'],
            ['data_id' => '313', 'network' => 'AIRTEL', 'plan_type' => 'SME', 'amount' => '1050', 'size' => '3.0GB', 'validity' => '7 days'],
            ['data_id' => '217', 'network' => 'MTN', 'plan_type' => 'GIFTING', 'amount' => '2525', 'size' => '6.0 GB', 'validity' => '7 Days'],
            ['data_id' => '307', 'network' => 'MTN', 'plan_type' => 'GIFTING', 'amount' => '50000', 'size' => '2000 GB', 'validity' => '60 Days'],
            ['data_id' => '309', 'network' => 'MTN', 'plan_type' => 'GIFTING', 'amount' => '3000', 'size' => '7.0 GB', 'validity' => '7 Days'],
            ['data_id' => '215', 'network' => 'MTN', 'plan_type' => 'SME', 'amount' => '650', 'size' => '1.0 GB', 'validity' => '1 Days'],
            ['data_id' => '345', 'network' => 'MTN', 'plan_type' => 'GIFTING', 'amount' => '1555', 'size' => '2.0 GB', 'validity' => '30 Days'],
            ['data_id' => '307', 'network' => 'MTN', 'plan_type' => 'SME', 'amount' => '500', 'size' => '200.0GB', 'validity' => '60 day +10.5min'],
            ['data_id' => '216', 'network' => 'MTN', 'plan_type' => 'SME', 'amount' => '1300', 'size' => '2.0GB', 'validity' => '30 Days'],
            ['data_id' => '364', 'network' => 'MTN', 'plan_type' => 'GIFTING', 'amount' => '600', 'size' => '1.5 GB', 'validity' => '2 days'],
            ['data_id' => '365', 'network' => 'MTN', 'plan_type' => 'GIFTING', 'amount' => '1000', 'size' => '1.5 GB', 'validity' => '7 days'],
            ['data_id' => '362', 'network' => 'MTN', 'plan_type' => 'GIFTING', 'amount' => '1555', 'size' => '3.0 GB +N1500 for call+100 SMS', 'validity' => '30 days'],
            ['data_id' => '306', 'network' => 'MTN', 'plan_type' => 'SME', 'amount' => '21000', 'size' => '75GB', 'validity' => '30 days'],
            ['data_id' => '316', 'network' => 'MTN', 'plan_type' => 'GIFTING', 'amount' => '827', 'size' => '2.0GB', 'validity' => '7 days'],
            ['data_id' => '317', 'network' => 'MTN', 'plan_type' => 'SME', 'amount' => '1200', 'size' => '2.5GB', 'validity' => '7 Days'],
            ['data_id' => '318', 'network' => 'MTN', 'plan_type' => 'MTN SME BOSS', 'amount' => '1000', 'size' => '2.0 GB', 'validity' => '7 DAYS'],
            ['data_id' => '320', 'network' => 'MTN', 'plan_type' => 'MTN SME BOSS', 'amount' => '100', 'size' => '110MB', 'validity' => '1 DAY'],
            ['data_id' => '321', 'network' => 'MTN', 'plan_type' => 'MTN SME BOSS', 'amount' => '70', 'size' => '75.0 MB', 'validity' => '1 Day'],
            ['data_id' => '324', 'network' => 'MTN', 'plan_type' => 'MTN SME BOSS', 'amount' => '10000', 'size' => '40.0 GB', 'validity' => '60 days'],
            ['data_id' => '326', 'network' => 'MTN', 'plan_type' => 'SME', 'amount' => '40800', 'size' => '150.0 GB', 'validity' => '60 days'],
            ['data_id' => '327', 'network' => 'MTN', 'plan_type' => 'MTN SME BOSS', 'amount' => '26500', 'size' => '90.0 GB', 'validity' => '60 day +10.5min'],
        ];

        foreach ($data as $item) {
            $item['created_at'] = now();
            $item['updated_at'] = now();
            \Illuminate\Support\Facades\DB::table('sme_datas')->insert($item);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sme_datas');
    }
};
