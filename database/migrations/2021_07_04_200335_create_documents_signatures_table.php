<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsSignaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_signatures', function (Blueprint $table) {
            $table->id();
            $table->longText('sign');
            $table->longText('hash');
            $table->longText('data');
            $table->longText('cert');
            $table->longText('result');
            $table->foreignId('document_id')->constrained('documents')->cascadeOnDelete();
            $table->integer('user_id', false);
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
        Schema::dropIfExists('document_signatures');
    }
}
