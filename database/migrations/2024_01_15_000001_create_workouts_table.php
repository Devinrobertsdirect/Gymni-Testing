<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workouts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('category', ['strength', 'cardio', 'hiit', 'mobility', 'yoga', 'pilates', 'crossfit', 'other']);
            $table->enum('difficulty', ['beginner', 'intermediate', 'advanced']);
            $table->integer('duration')->nullable(); // in minutes
            $table->string('equipment')->nullable();
            $table->json('muscle_groups')->nullable(); // array of muscle groups
            $table->text('instructions')->nullable();
            $table->string('video_url')->nullable();
            $table->string('image_url')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->boolean('is_public')->default(false);
            $table->json('tags')->nullable(); // array of tags
            $table->integer('calories_burned')->nullable();
            $table->integer('sets')->nullable();
            $table->integer('reps')->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->integer('rest_time')->nullable(); // in seconds
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            
            // Indexes
            $table->index('category');
            $table->index('difficulty');
            $table->index('is_public');
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workouts');
    }
}
