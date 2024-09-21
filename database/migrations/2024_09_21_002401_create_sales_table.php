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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salesman_id')->comment('salesman_id is equal admin_id');
            $table->foreignId('client_id');
            $table->foreignId('list_products_id');
            $table->decimal('total_price', places: 2);
            $table->timestamps();
            
            $table->foreign('salesman_id')->references('id')->on('admins')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('list_products_id')->references('id')->on('list_products_sales')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
