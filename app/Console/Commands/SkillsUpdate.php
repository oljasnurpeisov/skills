<?php

namespace App\Console\Commands;

use App\Models\Skill;
use Illuminate\Console\Command;
use Orchestra\Parser\Xml\Facade as XmlParser;

class SkillsUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:skills';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and Update current skills';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $xml = XmlParser::load(url('https://iac2:Iac2007RBD@www.enbek.kz/feed/resume/cl_hard_skills.xml'));

        $skills = $xml->parse([
            'data' => ['uses' => 'row[field(::name=@)]'],
        ]);

        foreach (array_values($skills)[0] as $skill) {
            if($skill['fl_check'] == 'x'){
                $skill['fl_check'] = 0;
            }else{
                $skill['fl_check'] = 1;
            }
            $user = Skill::updateOrCreate([
                'code_skill' => $skill['codskill']
            ], [
                'fl_check' => $skill['fl_check'],
                'name_ru' => $skill['name_skill'],
                'name_kk' => $skill['name_skill_kz'],
                'fl_show' => $skill['fl_show'],
                'uid' => $skill['uid'],
            ]);
        }
        return '';
    }
}
