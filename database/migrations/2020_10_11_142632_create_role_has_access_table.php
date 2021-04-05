<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleHasAccessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_has_access', function (Blueprint $table) {
            $table->id();
            //$table->integer('role_id');
            //$table->integer('access_id');
            $table->timestampsTz(); //time stamp with timezone in UTC
            $table->softDeletesTz('deleted_at', 0);
            $table->unsignedBigInteger('role_id')->nullable();  
            $table->unsignedBigInteger('access_id')->nullable();  
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('access_id')->references('id')->on('access')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_has_access');
    }
}
