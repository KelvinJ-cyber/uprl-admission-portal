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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('jamb_reg_number')->unique();
            $table->string('surname');
            $table->string('first_name');
            $table->string('other_names')->nullable();
            $table->string('gender');
            $table->date('date_of_birth');
            $table->string('state_of_origin');
            $table->string('local_government');
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->unsignedSmallInteger('utme_score');
            $table->string('subject_combination')->nullable();
            $table->string('status')->default('eligible_for_screening');
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
