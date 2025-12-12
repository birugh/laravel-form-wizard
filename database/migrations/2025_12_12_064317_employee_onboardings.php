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
        Schema::create('employee_onboardings', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['draft', 'submitted'])->default('draft');

            // Step 1
            $table->json('personal_information');

            // Step 2
            $table->json('job_details')->nullable();

            // Step 3
            $table->json('access_rights')->nullable();

            // Step 4
            $table->json('evidences')->nullable();

            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_onboardings');
    }
};
