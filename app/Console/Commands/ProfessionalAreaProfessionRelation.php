<?php

namespace App\Console\Commands;

use App\Models\ProfessionalArea;
use App\Models\Professions;
use Illuminate\Console\Command;
use Orchestra\Parser\Xml\Facade as XmlParser;

class ProfessionalAreaProfessionRelation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:professional_area_profession_relation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and Update current professional areas professions relation';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $xml = XmlParser::load(url('https://iac2:Iac2007RBD@www.enbek.kz/feed/resume/profobl_link_prf.xml'));

        $profession_skills = $xml->parse([
            'data' => ['uses' => 'row[field(::name=@)]'],
        ]);
        foreach (array_values($profession_skills)[0] as $profession_skill) {
            $professional_area = ProfessionalArea::where('code', '=', $profession_skill['cod_profObl'])->first();
            $profession = Professions::where('code', '=', $profession_skill['cod_NKZ'])->first();
            if ($professional_area and $profession_skills and $profession) {
                $professional_area->professions()->sync([$profession->id], false);
            }
        }
        return '';
    }
}
