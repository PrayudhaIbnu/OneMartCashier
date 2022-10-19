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
        //
        Schema::create('faktur', function (Blueprint $table) {
            $table->engine = 'innodb';
            $table->integer('id_faktur')->primary();
            $table->integer('penjualan');
            $table->integer('jml_tunai');
            $table->integer('jml_kembalian');

            $table
                ->foreign('penjualan')
                ->references('id_penjualan')
                ->on('penjualan')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('faktur');
    }
};
