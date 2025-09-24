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
        Schema::create('job_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('requirements');
            $table->string('salary')->nullable();
            $table->string('location');
            $table->string('department');
            $table->enum('employment_type', ['full-time', 'part-time', 'contract'])->default('full-time');
            $table->enum('experience_level', ['entry', 'junior', 'junior/mid', 'mid', 'mid/senior', 'senior', 'lead', 'mentor'])->nullable();
            $table->enum('position', ['office', 'work-from-home', 'hybrid', 'remote', 'worldwide'])->default('office');
            $table->enum('status', ['open', 'closed', 'draft'])->default('open');
            $table->timestamps();

            $table->unique(['user_id', 'title']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Disable foreign constraints if need it
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('user_id');
        Schema::dropIfExists('category_id');
        Schema::enableForeignKeyConstraints();

        Schema::dropIfExists('job_posts');
    }
};
