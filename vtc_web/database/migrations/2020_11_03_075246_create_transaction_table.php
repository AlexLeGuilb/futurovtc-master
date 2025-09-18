<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction', function (Blueprint $table) {
            $table->id("idTransaction");
            $table->string("numTransaction", 20)->unique();
            $table->date("dateTransaction");
            $table->string("gpsDepart", 25);
            $table->string("gpsArrive", 25);
            $table->date("dateDepart");
            $table->time("heureDepart");
            $table->date("dateArrivee")->nullable();
            $table->time("heureArrivee")->nullable();
            $table->boolean("courseEffectuee");
            $table->integer("nbPassager");
            $table->foreignId("idClient");
            $table->foreignId("idPaiement");

            $table->foreign("idClient")->references("idClient")->on("Client")->onUpdate("cascade");
            $table->foreign("idPaiement")->references("idPaiement")->on("Paiement")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction');
    }
}
