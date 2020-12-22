<?php

namespace App\Console\Commands;

use App\Models\Professions;
use Illuminate\Console\Command;
use Orchestra\Parser\Xml\Facade as XmlParser;

class ProfessionsParentUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:professions_parent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and Update professions parent';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $items = Professions::whereRaw('LENGTH(code) = 10')->get();

        foreach ($items as $item) {
            $code_group_profession = substr($item->code, 0, -4);
            $profession = Professions::where('code', '=', $code_group_profession)->first();
            if ($profession) {
                $item->parent_id = $profession->id;
                $item->save();
            }
        }
        return '';
    }
}
