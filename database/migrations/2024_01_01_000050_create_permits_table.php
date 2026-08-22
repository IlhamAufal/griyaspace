<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->unique()->constrained('bookings')->restrictOnDelete();
            $table->string('permit_number')->unique();
            $table->string('verification_token')->unique();
            $table->string('status')->default('valid');
            $table->string('pdf_storage_key');
            $table->timestamp('issued_at');
            $table->timestamp('revoked_at')->nullable();
            $table->text('revocation_reason')->nullable();
            $table->string('template_version');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permits');
    }
};
