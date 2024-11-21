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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id') // This will link the schedule to the doctor (user with 'doctor' role)
            ->constrained()
            ->onDelete('cascade');
      $table->date('start_date');
      $table->time('start_time');
      $table->time('end_time');
      $table->string('docname');
      $table->enum('status', ['available', 'already taken'])->default('available'); // Status field
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
