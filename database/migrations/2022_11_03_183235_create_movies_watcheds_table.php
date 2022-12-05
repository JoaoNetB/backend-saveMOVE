<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies_watcheds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->references('id')
                    ->on('users')->onDelete('cascade');
            $table->integer('id_move');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movies_watcheds');
    }
};
