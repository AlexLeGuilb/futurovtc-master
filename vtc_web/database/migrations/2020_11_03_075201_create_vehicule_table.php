<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiculeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicule', function (Blueprint $table) {
            $table->string("idVehicule", 9)->primary();
            $table->string("marque", 20);
            $table->string("model", 20);
            $table->string("couleur", 20);
            $table->integer("nbPlace");
            $table->string("etatVh", 30);
            $table->double("kmVh");
            $table->integer("kmConstucteurRevision");
            $table->double("kmMomentRevision");
            $table->string("typePermis", 3);

            $table->foreign("typePermis")->references("typePermis")->on("permis")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicule');
    }
}
