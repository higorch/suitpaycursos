<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulidMorphs('attachable');
            $table->text('path');
            $table->string('extension', 8);
            $table->unsignedBigInteger('size');
            $table->string('taxonomy', 80)->nullable(); // user-avatar, course-thumb
            $table->string('status', 25)->index(); // activated, disabled, revision
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
