<?php

use App\Models\Author;
use App\Models\Category;
use App\Models\NewsAgency;
use App\Models\Source;
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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('unique_id_on_source')->nullable();
            $table->string('web_url_on_source')->nullable();
            $table->dateTime('publish_date')->nullable();
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->foreignIdFor(NewsAgency::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Source::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Category::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Author::class)->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
