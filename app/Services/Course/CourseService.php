<?php

namespace Services\Course;

use App\Models\Course;
use Illuminate\Pagination\LengthAwarePaginator;
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

    /**
     * Ожидающие проверки договора
     *
     * @return LengthAwarePaginator
     */
    public function waitCheckContracts(): LengthAwarePaginator
    {
        return Course::checkContracts()->paginate(10);
    }

    /**
     * Ожидающие подписания договора со стороны Автора
     *
     * @return LengthAwarePaginator
     */
    public function waitSigningAuthor(): LengthAwarePaginator
    {
        return Course::signingAuthor()->paginate(10);
    }

    /**
     * Ожидающие подписания договора со стороны Администрации
     *
     * @return LengthAwarePaginator
     */
    public function waitSigningAdmin(): LengthAwarePaginator
    {
        return Course::signingAdmin()->paginate(10);
    }
}
