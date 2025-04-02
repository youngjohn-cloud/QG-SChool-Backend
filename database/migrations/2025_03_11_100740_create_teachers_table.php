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
        Schema::disableForeignKeyConstraints();
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('teacher_id')->nullable();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->unique();
            $table->enum('gender', ['MALE', 'FEMALE', 'OTHERS'])->default('FEMALE');
            $table->string('address');
            $table->date('dob');
            $table->string('qualifications')->nullable();
            $table->string('teacher_dp')->nullable();
            $table->string('blood_type')->nullable();
            $table->date('hire_date')->nullable();
            $table->string('role')->default('teacher');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('teachers');
    }
};
