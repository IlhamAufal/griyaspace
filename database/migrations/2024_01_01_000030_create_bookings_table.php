<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique();
            $table->foreignId('organization_id')->constrained('organizations')->restrictOnDelete();
            $table->foreignId('room_id')->constrained('rooms')->restrictOnDelete();
            $table->string('activity_name');
            $table->text('purpose');
            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('participant_count');
            $table->string('person_in_charge');
            $table->string('contact_phone');
            $table->string('status')->default('submitted');
            $table->text('admin_note')->nullable();
            $table->foreignId('submitted_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('decided_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->index(['room_id', 'booking_date', 'start_time', 'end_time'], 'idx_room_schedule');
            $table->index('status');
            $table->index('organization_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
