<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->integer('version');
            $table->string('storage_key');
            $table->string('original_filename');
            $table->string('mime_type');
            $table->integer('file_size');
            $table->string('checksum');
            $table->foreignId('uploaded_by')->constrained('users')->restrictOnDelete();
            $table->timestamp('uploaded_at');
            $table->boolean('is_current')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_documents');
    }
};
