<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffecteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affecte', function (Blueprint $table) {
            $table->foreignId("id");
            $table->foreignId("idTransaction");
            $table->string("idVehicule", 9);

            $table->primary(['id', 'idTransaction', 'idVehicule'], "empVehTransaction");

            $table->foreign("id")->references("id")->on("users");
            $table->foreign("idVehicule")->references("idVehicule")->on("vehicule");
            $table->foreign("idTransaction")->references("idTransaction")->on("transaction");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('affecte');
    }
}
