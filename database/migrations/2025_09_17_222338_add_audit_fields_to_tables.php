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

        // Crear tabla de auditoría de transacciones
        Schema::create('transaction_audits', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('transaction_type', 50); // 'payment_order', 'balance_update', etc.
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('related_id')->nullable(); // ID de la orden de pago, etc.
            $table->string('related_type', 100)->nullable(); // Modelo relacionado
            $table->decimal('amount', 15, 2)->nullable();
            $table->decimal('balance_before', 15, 2)->nullable();
            $table->decimal('balance_after', 15, 2)->nullable();
            $table->string('status', 20); // 'successful', 'failed', 'pending'
            $table->text('description')->nullable();
            $table->json('metadata')->nullable(); // Datos adicionales
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->index(['client_id', 'transaction_type']);
            $table->index(['related_id', 'related_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_audits');
    }
};
