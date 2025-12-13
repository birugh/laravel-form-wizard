<?php

use App\OnboardingStatus;
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

            // Status lifecycle
            $table->string('status')
                ->default(OnboardingStatus::DRAFT->value);

            // STEP 1 (WAJIB ADA)
            $table->json('personal_information');

            // STEP 2–4 (BOLEH NULL SAAT DRAFT)
            $table->json('job_details')->nullable();
            $table->json('access_rights')->nullable();

            // Admin creator
            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            // Locking mechanism
            $table->timestamp('submitted_at')->nullable();

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
