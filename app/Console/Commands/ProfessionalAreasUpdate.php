<?php

namespace App\Console\Commands;

use App\Models\ProfessionalArea;
use Illuminate\Console\Command;
use Orchestra\Parser\Xml\Facade as XmlParser;

class ProfessionalAreasUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:professional_areas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and Update current professional areas';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $xml = XmlParser::load(url('https://iac2:Iac2007RBD@www.enbek.kz/feed/resume/profobl.xml'));

        $professional_areas = $xml->parse([
            'data' => ['uses' => 'row[field(::name=@)]'],
        ]);

        foreach ($professional_areas['data'] as $professional_area) {

            ProfessionalArea::updateOrCreate([
                'code' => $professional_area['cod']
            ], [
                'name_ru' => $professional_area['name_rus'],
                'name_kk' => $professional_area['name_kaz'],
            ]);
        }
        return '';
    }
}
