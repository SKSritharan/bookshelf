<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('isbn');
            $table->string('isbn13')->nullable();
            $table->text('description')->nullable();
            $table->string('cover')->nullable();
            $table->enum('language',
                array_column(\App\Enums\Language::cases(), 'value'))->default(\App\Enums\Language::English->value);
            $table->integer('available_copies');
            $table->integer('total_copies');
            $table->foreignId('author_id');
            $table->foreignId('category_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
