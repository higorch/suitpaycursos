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
        Schema::create('courses', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->unsignedBigInteger('creator_id')->nullable();
            $table->foreign('creator_id')->references('id')->on('users')->onUpdate('cascade');
            $table->string('name')->nullable()->index();
            $table->text('description')->nullable();
            $table->string('slug', 100)->index();
            $table->string('presentation_video_url')->nullable();
            $table->string('status', 25)->index(); // activated, disabled
            $table->enum('delivery_mode', ['online', 'in-person', 'hybrid']); // O curso pode on-line, presencial ou hibrido.
            $table->unsignedInteger('max_enrollments')->nullable(); // maximo de matriculas permitidas
            $table->timestamp('enrollment_deadline')->nullable(); // data maxima para se matricular
            $table->timestamps();

            $table->unique(['creator_id', 'name', 'slug'], 'courses_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
