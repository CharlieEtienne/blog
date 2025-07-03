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
        Schema::create('tag_post', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tag_id')->index();
            $table->foreignId('post_id')->index();
        });
    }
};
