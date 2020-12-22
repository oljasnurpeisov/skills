<?php

namespace App\Console\Commands;

use App\Models\Professions;
use Illuminate\Console\Command;
use Orchestra\Parser\Xml\Facade as XmlParser;

class ProfessionsUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:professions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and Update current professions';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $xml = XmlParser::load(url('https://iac2:Iac2007RBD@www.enbek.kz/feed/resume/cl_prof.xml'));

        $professions = $xml->parse([
            'data' => ['uses' => 'row[field(::name=@)]'],
        ]);

        foreach ($professions['data'] as $profession) {
            if ((strlen($profession['Column1']) == 6) || (strlen($profession['Column1']) == 10)) {

                $user = Professions::updateOrCreate([
                    'code' => $profession['Column1']
                ], [
                    'name_ru' => $profession['prof_name'],
//                    'name_kk' => $profession['NAME_KR_K'],

                ]);
            }
        }
        return '';
    }
}
