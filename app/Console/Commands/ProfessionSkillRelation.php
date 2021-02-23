<?php

namespace App\Console\Commands;

use App\Models\Professions;
use App\Models\Skill;
use Illuminate\Console\Command;
use Orchestra\Parser\Xml\Facade as XmlParser;

class ProfessionSkillRelation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:profession_skill_relation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and Update current professions skills relation';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $xml = XmlParser::load(url('https://iac2:Iac2007RBD@www.enbek.kz/feed/resume/cl_prof_hard_skills.xml'));

        $profession_skills = $xml->parse([
            'data' => ['uses' => 'row[field(::name=@)]'],
        ]);
        foreach (array_values($profession_skills)[0] as $profession_skill) {
            $profession = Professions::where('code', '=', $profession_skill['cod_nkz'])->first();
            $skill = Skill::where('code_skill', '=', $profession_skill['codcomp'])->first();
            if ($skill and $profession_skills and $profession) {
                $profession->skills()->sync([$skill->id], false);
            }
        }
        return '';
    }
}
