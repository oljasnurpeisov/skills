<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterActsTableAddDocumentId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('avrs', function (Blueprint $table) {

            $table->foreignId('document_id')
                ->nullable()
                ->after('course_id')
                ->constrained('documents')
                ->nullOnDelete();

            $table->dateTime('signed_at')
                ->nullable()
                ->after('route_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropColumns('avrs', ['document_id', 'signed_at']);
    }
}
