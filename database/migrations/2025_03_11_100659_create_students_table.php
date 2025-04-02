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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->nullable();
            $table->foreignId('guardian_id')->constrained('users')->onDelete('cascade');
            $table->foreignId("class_id")->constrained('qg__classes')->onDelete('cascade');
            $table->foreignId("level_id")->constrained('levels')->onDelete('cascade');
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('lastname');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->unique();
            $table->enum('gender', ['male', 'female', 'others'])->default('female');
            $table->string('blood_type')->nullable();
            $table->date('dob')->nullable();
            $table->string('home_address');
            $table->string('department')->nullable();
            $table->string('student_dp')->nullable();
            $table->string('role')->default('student');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
