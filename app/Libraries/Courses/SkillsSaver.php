<?php

namespace Libraries\Courses;

use App\Models\CourseSkill;
use App\Models\ProfessionalAreaProfession;
use App\Models\Professions;
use App\Models\ProfessionSkill;
use \Illuminate\Support\Collection;

/**
 * Class SkillsSaver
 * @author kgurovoy@gmail.com
 * @package Libraries\Courses
 */

class SkillsSaver {
    /**
     * @var int
     */
    private $item;

    /**
     * @var array
     */
    private $request;

    /**
     * @var Collection
     */
    private $collection;

    /**
     * @var CourseSkill
     */
    private $courseSkill;

    /**
     * SkillsSaver constructor.
     *
     * @param object $item
     * @param array $request
     */
    public function __construct(object $item, array $request)
    {
        $this->item         = $item;
        $this->request      = $request;
        $this->collection   = collect([]);
        $this->courseSkill  = new CourseSkill();
    }

    /**
     * Сохраняем навыки
     *
     * @return void
     */
    public function save(): void
    {
        $this->restorationSkillPath();
        $this->checkProfessions();
        $this->checkProfessionAreas();
        $this->saveAll();
    }

    /**
     * Восстановление дерева навыка
     *
     * @return void
     */
    private function restorationSkillPath(): void
    {
        foreach($this->request['skills'] as $skill)
        {
            $skill              = ProfessionSkill::whereSkillId($skill)->first();
            $profession         = Professions::whereParentId($skill->profession_id)->whereIn('id', $this->request['professions'])->first();

            if (!empty($profession)) {
                $professionalArea = ProfessionalAreaProfession::whereProfessionId($profession->id)->first();

                $this->collection->add([
                    'skill_id' => $skill->skill_id,
                    'profession_id' => $profession->id,
                    'professional_area_id' => $professionalArea->professional_area_id
                ]);
            }
        }
    }

    /**
     * Проверяем все ли профессии добавили
     *
     * @return void
     */
    private function checkProfessions(): void
    {
        foreach($this->request['professions'] as $profession)
        {
            if ($this->collection->where('profession_id', $profession)->count() == 0)
            {
                $professionalArea = ProfessionalAreaProfession::whereProfessionId($profession)->first();

                $this->collection->add([
                    'skill_id'              => null,
                    'profession_id'         => $profession,
                    'professional_area_id'  => $professionalArea->professional_area_id
                ]);
            }
        }
    }

    /**
     * Проверяем все ли области добавили
     *
     * @return void
     */
    private function checkProfessionAreas(): void
    {
        foreach($this->request['professional_areas'] as $area)
        {
            if ($this->collection->where('professional_area_id', $area)->count() == 0)
            {
                $this->collection->add([
                    'skill_id'              => null,
                    'profession_id'         => null,
                    'professional_area_id'  => $area
                ]);
            }
        }
    }

    /**
     * Сохраняем навыки
     *
     * @return void
     */
    private function saveAll(): void
    {
        foreach ($this->collection as $item) {
            $this->courseSkill->create($item + ['course_id' => $this->item->id]);
        }
    }
}
