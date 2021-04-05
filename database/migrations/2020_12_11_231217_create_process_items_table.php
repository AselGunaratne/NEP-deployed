<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_items', function (Blueprint $table) {
            $table->id();
            $table->integer('form_id');
            $table->string('remark');
            $table->boolean('prerequisite'); //false
            $table->timestampsTz(); //time stamp with timezone in UTC
            $table->softDeletesTz('deleted_at', 0);

            $table->string('ext_requestor');
            $table->string('ext_requestor_email');

            $table->unsignedBigInteger('request_organization')->nullable();
            $table->foreign('request_organization')->references('id')->on('organizations')->onDelete('cascade');

            $table->unsignedBigInteger('activity_organization')->nullable();
            $table->foreign('activity_organization')->references('id')->on('organizations')->onDelete('cascade');

            $table->unsignedBigInteger('activity_user_id')->nullable();
            $table->foreign('activity_user_id')->references('id')->on('users')->onDelete('cascade');
          
            $table->unsignedBigInteger('form_type_id')->nullable();
            $table->foreign('form_type_id')->references('id')->on('form_types')->onDelete('cascade');

            $table->unsignedBigInteger('prerequisite_id')->nullable();
            $table->foreign('prerequisite_id')->references('id')->on('process_items')->onDelete('cascade');

            $table->unsignedBigInteger('status_id')->nullable();
            $table->foreign('status_id')->references('id')->on('status')->onDelete('cascade');

            $table->unsignedBigInteger('created_by_user_id')->nullable();
            $table->foreign('created_by_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('process_items');
    }
}
