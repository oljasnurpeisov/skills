<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentTypesSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('document_types')->updateOrInsert(
            [
                'code' => 'contract',
                'name' => 'Договор'
            ]
        );

        DB::table('document_types')->updateOrInsert(
            [
                'code' => 'act',
                'name' => 'Акт выполненных работ'
            ]
        );
    }
}
