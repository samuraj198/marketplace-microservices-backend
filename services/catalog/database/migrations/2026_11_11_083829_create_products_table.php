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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->text("description");
            $table->foreignId("category_id")->constrained("categories")->onDelete("cascade");
            $table->unsignedInteger("quantity")->default(0);
            $table->string("img")->nullable();
            $table->decimal("price", 10, 2)->unsigned();
            $table->string("article")->unique();
            $table->timestamps();

            $table->softDeletes();
            $table->fullText("name");
            $table->index(["category_id", "price"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
