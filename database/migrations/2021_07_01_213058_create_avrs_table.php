<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avrs', function (Blueprint $table) {
            $table->id();
            $table->integer('number')->nullable()->index();
            $table->integer('contract_id')->index();
            $table->integer('course_id')->index();
            $table->string('link')->nullable();
            $table->string('invoice_link')->nullable();
            $table->integer('status')->default(0)->index();
            $table->integer('sum')->default(0)->index();
            $table->date('start_at')->index();
            $table->date('end_at')->index();
            $table->integer('route_id')->default(null)->index();
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
        Schema::dropIfExists('avrs');
    }
}
