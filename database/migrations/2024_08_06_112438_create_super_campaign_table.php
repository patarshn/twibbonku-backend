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
        Schema::create('super_campaigns', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('twibbon_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_date');
            $table->integer('price_per_day');
            $table->integer('total_price');
            $table->integer('subscription_code');
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('super_campaigns');
    }
};
