<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterContractsTableAddDocumentId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contracts', function (Blueprint $table) {

            $table->foreignId('document_id')
                ->nullable()
                ->after('course_id')
                ->constrained('documents')
                ->nullOnDelete();

//            $table->dateTime('signed_at')
//                ->nullable()
//                ->after('reject_comment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropColumns('contracts', ['document_id', 'signed_at']);
    }
}
