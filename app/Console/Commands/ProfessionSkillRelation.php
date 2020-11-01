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
        $xml = XmlParser::load(url('https://iac2:Iac2007RBD@www.enbek.kz/feed/resume/prof_hardskills_links.xml'));

        $profession_skills = $xml->parse([
            'data' => ['uses' => 'row[field(::name=@)]'],
        ]);
        foreach (array_values($profession_skills)[0] as $profession_skill) {
            $profession = Professions::where('cod_nkz', '=', $profession_skill['codprof'])->first();
            $skill = Skill::where('code_skill', '=', $profession_skill['codskill'])->first();
            if (!empty($skill) and !empty($profession_skills)) {
                $profession->skills()->sync([$skill->id], false);
            }
        }
        return '';
    }
}
