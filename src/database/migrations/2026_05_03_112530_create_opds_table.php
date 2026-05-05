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
        Schema::create('opds', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('dimension_id')->constrained('dimensions')->cascadeOnDelete();
            // $table->foreignId('sub_dimension_id')->nullable()->constrained();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('email')->nullable();
            $table->text('description')->nullable();
            $table->text('iso_standard')->nullable();
            $table->decimal('weight', 5, 2)->default(1.0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('year')->default(date('Y'));
            // $table->timestamp('joined_at')->nullable();
            $table->foreignId('task_owner_id')->nullable()->constrained('opds');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opds');
    }
};
