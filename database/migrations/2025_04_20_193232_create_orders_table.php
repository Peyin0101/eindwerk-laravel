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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('voornaam');
            $table->string('naam');
            $table->string('straat');
            $table->string('huisnummer');
            $table->string('postcode');
            $table->string('woonplaats');
            $table->string('payment_method')->nullable();
            $table->foreignId('discount_code_id')->nullable()->constrained('discountcodes')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
