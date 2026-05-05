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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opd_id')->constrained();
            $table->foreignId('indicator_id')->constrained();
            $table->integer('year');
            $table->text('answer')->nullable();
            $table->text('additional_notes')->nullable();
            $table->decimal('survey_score', 5, 2)->nullable();
            $table->decimal('final_score', 5, 2)->nullable();
            $table->enum('status', [
                'draft', 'submitted', 'review', 'revisi', 'approved'
            ])->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
