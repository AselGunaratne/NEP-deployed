<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandParcelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('land_parcels', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unique('title');
            //$table->json('governing_organizations');
            $table->json('polygon');
            $table->integer('created_by_user_id');
            $table->tinyInteger('protected_area');
            $table->timestampsTz(); //time stamp with timezone in UTC
            $table->softDeletesTz('deleted_at', 0);

            $table->unsignedBigInteger('activity_organization')->nullable();
            $table->foreign('activity_organization')->references('id')->on('organizations')->onDelete('cascade');

            $table->unsignedBigInteger('status_id')->nullable();
            $table->foreign('status_id')->references('id')->on('status')->onDelete('cascade');

            $table->unsignedBigInteger('district_id')->nullable();
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');

            $table->unsignedBigInteger('gs_division_id')->nullable();
            $table->foreign('gs_division_id')->references('id')->on('gs_divisions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('land_parcels');
    }
}
