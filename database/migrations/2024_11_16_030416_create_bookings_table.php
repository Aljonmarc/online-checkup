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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained()->onDelete('cascade'); // Foreign key to schedules table
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key to users table
            $table->string('patient_name'); 
            $table->string('patient_phone'); 
            $table->enum('status', ['booked', 'cancelled', 'completed']); // Status of the booking
            $table->date('start_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('doc_name')->nullable();
            $table->string('reply')->nullable();
            $table->enum('admin_status', ['pending', 'approved', 'canceled'])->default('pending');
            $table->unsignedBigInteger('schedule_user_id');  // This stores the user_id from the schedule
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
