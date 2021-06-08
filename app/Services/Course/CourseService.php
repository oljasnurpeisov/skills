<?php

namespace Services\Course;

use Libraries\Courses\SkillsSaver;

/**
 * Class CourseService
 * @author kgurovoy@gmail.com
 * @package Services\Course
 */
class CourseService {
    /**
     * Сохраняем навыки
     *
     * @param object $item
     * @param array $request
     */
    public function saveSkillsTree(object $item, array $request): void
    {
        (new SkillsSaver($item, $request))->save();
    }

    /**
     * Обновляем навыки
     *
     * @param object $item
     * @param array $request
     */
    public function updateSkillsTree(object $item, array $request): void
    {
        $item->skills()->detach();

        (new SkillsSaver($item, $request))->save();
    }
}
