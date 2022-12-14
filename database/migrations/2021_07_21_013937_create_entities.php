<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entities', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->string('code')->unique();
            $table->json('divisions')->foreign('division_id')->references('id')->on('divisions');
            $table->string('group')->foreign('group_id')->references('id')->on('groups');
            $table->json('attribute_values')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entities');
    }
}
