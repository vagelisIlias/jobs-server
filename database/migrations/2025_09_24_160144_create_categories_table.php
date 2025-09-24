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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        // Drop individual columns if needed
        Schema::table('categories', function (Blueprint $table){
            $table->dropColumn('job_post_id');
            $table->dropColumn('name');
            $table->dropColumn('slug');
            $table->dropColumn('description');
        });

        // Disable foreign constraints if need it
        // Schema::disableForeignKeyConstraints();
        // Schema::dropIfExists('user_id');
        // Schema::dropIfExists('job_posts');
        // Schema::enableForeignKeyConstraints();

        Schema::dropIfExists('categories');
    }
};
