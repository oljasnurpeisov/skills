<?php

namespace Services\Course;

use Libraries\Courses\SkillsSaver;

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
}
