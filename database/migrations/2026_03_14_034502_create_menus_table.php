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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('menu_name');
            $table->string('slug')->nullable();
            $table->string('route')->nullable();
            $table->foreignId('parent_id')->nullable();
            $table->string('icon')->nullable();
            $table->integer('order_number')->default(0);

            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();

            // created_at, updated_at, deleted_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
