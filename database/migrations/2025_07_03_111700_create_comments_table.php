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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index();
            $table->foreignId('post_id')->index();
            $table->foreignId('parent_id')->nullable()->index();
            $table->text('content');
            $table->datetime('modified_at')->nullable();
            $table->timestamps();
        });
    }
};
