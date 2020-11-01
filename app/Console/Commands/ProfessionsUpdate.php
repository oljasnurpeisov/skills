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
        $xml = XmlParser::load(url('https://iac2:Iac2007RBD@www.enbek.kz/feed/resume/sprprf_uoz.xml'));

        $professions = $xml->parse([
            'data' => ['uses' => 'row[field(::name=@)]'],
        ]);

        foreach (array_values($professions)[0] as $profession) {

            $user = Professions::updateOrCreate([
                'cod' => $profession['COD']
            ], [
                'cod_nkz' => $profession['COD_NKZ'],
                'name_ru' => $profession['NAME_KR_R'],
                'name_kk' => $profession['NAME_KR_K'],
                'text_ru' => $profession['TEXT_R'],
                'text_kk' => $profession['TEXT_K'],
            ]);
        }
        return '';
    }
}
