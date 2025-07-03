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
        Schema::create('posts', function (Blueprint $table) {

            // Common Fields
            $table->id();
            $table->timestamps();

            // Post Fields
            $table->string('title');
            $table->text('body')->nullable();
            $table->string('slug')->unique();
            $table->string('excerpt')->nullable();
            $table->string('image')->nullable();
            $table->timestamp('published_at')->nullable();

            // Post Relations
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();

        });
    }
};
