<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubMenusTable extends Migration
{
    public function up()
    {
        Schema::create('submenus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id');
            $table->string('title');
            $table->string('to')->nullable();
            $table->string('icon')->nullable();
            $table->timestamps();

            // Menambahkan foreign key constraint ke tabel menu utama dengan ON DELETE CASCADE
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('submenus');
    }
}

