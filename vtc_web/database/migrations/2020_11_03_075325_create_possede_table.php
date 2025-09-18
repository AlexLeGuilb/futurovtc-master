<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePossedeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('possede', function (Blueprint $table) {
            $table->string("typePermis", 3);
            $table->foreignId("id");
            $table->primary(["typePermis", "id"], "permisEmploye");

            $table->foreign("typePermis")->references("typePermis")->on("permis");
            $table->foreign("id")->references("id")->on("users");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('possede');
    }
}
