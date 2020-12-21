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

        foreach ($skills['data'] as $skill) {
            $item = Skill::updateOrCreate([
                'code_skill' => $skill['codcomp']
            ], [
                'name_ru' => $skill['namecomp'],
                'name_kk' => $skill['namecomp_kz'],
            ]);
        }
        return '';
    }
}
