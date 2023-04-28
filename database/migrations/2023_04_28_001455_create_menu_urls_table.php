<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuUrlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_url', function (Blueprint $table) {
            $table->id();
            $table->string('name',45);
            $table->unsignedBigInteger('father')->default(0);
            $table->string('link',100);
            $table->unsignedInteger('order')->default(0);
            $table->string('icon',50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_url');
    }
}
