<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Bookdetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookdetails', function (Blueprint $table) {
            $table->increments('bdid');
            $table->foreignId('bid');
            $table->foreign('bid')->references('bid')->on('books');
            $table->foreignId('gid');
            $table->foreign('gid')->references('gid')->on('goods');
            $table->integer('amount')->default(0);
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
    }
}
