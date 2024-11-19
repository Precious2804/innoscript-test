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
        Schema::create('articles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('author')->nullable();
            $table->longText('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('url')->nullable();
            $table->string('image')->nullable();
            $table->dateTime('publication_date')->nullable();
            $table->string('api_resource')->nullable();
            $table->string('news_source')->nullable();
            $table->string('category')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
