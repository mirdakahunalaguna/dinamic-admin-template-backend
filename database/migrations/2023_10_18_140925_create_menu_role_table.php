<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuRoleTable extends Migration
{
    public function up()
    {
        Schema::create('menu_role', function (Blueprint $table) {
            $table->unsignedBigInteger('menu_id');
            $table->unsignedBigInteger('role_id');
            $table->timestamps();

            $table->unique(['menu_id', 'role_id']);

            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('menu_role');
    }
}
