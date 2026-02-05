<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable()->unique();
            $table->string('password')->nullable();
            $table->string('image')->nullable();
            $table->string('login_from')->nullable();
            $table->string('social_token')->nullable();

            $table->string('otp')->nullable();
            $table->timestamp('otp_verified_at')->nullable();
            $table->timestamp('otp_expired_at')->nullable();

            $table->enum('gender', ['male', 'female'])->nullable();
            $table->float('height')->nullable();
            $table->float('weight')->nullable();

            $table->enum('skin_tones', ['light', 'fair', 'medium', 'tan', 'dark'])->nullable();
            $table->enum('body_shape', ['hourglass', 'pear', 'apple', 'rectangle', 'inverted_triangle'])->nullable();

            $table->json('body_measurements')->nullable(); // shoulder - chest - waist - arm - hip - thigh - leg

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
