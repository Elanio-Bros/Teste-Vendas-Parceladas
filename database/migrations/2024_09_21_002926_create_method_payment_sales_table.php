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
        Schema::create('method_payment_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id');
            $table->boolean('paid')->default(False);
            $table->enum('type_payment', ['credit', 'cash'])->default('cash');
            $table->decimal('value', places: 2);
            $table->datetime('date_payment');
            $table->timestamps();

            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('method_payment_sales');
    }
};
