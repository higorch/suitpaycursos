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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->foreign('student_id')->references('id')->on('users')->onUpdate('cascade');
            $table->foreignUlid('course_id');
            $table->foreign('course_id')->references('id')->on('courses')->onUpdate('cascade');
            $table->unsignedBigInteger('registration_number'); // numero da matricula
            $table->string('status', 25)->index(); // activated, disabled
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
