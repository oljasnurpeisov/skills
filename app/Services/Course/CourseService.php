<?php

namespace Services\Course;

use App\Models\Course;
use Illuminate\Pagination\LengthAwarePaginator;
use Libraries\Courses\SkillsSaver;

/**
 * Class CourseService
 *
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
     * @todo refactor this query
     *
     * @return LengthAwarePaginator
     */
    public function waitCheckContracts(): LengthAwarePaginator
    {
        $free = Course::free()->whereDoesntHave('free_contract', function ($q) {
            return $q->pending();
        })->pluck('id');

        $paid = Course::paid()->whereDoesntHave('paid_contract', function ($q) {
            return $q->pending();
        })->pluck('id');

        $quota = Course::quota()->whereDoesntHave('quota_contract', function ($q) {
            return $q->pending();
        })->pluck('id');

//        dd($quota);

        return Course::whereIn('id', $free->merge($paid)->merge($quota))->whereNotIn('status', [0, 1, 2, 4])->latest()->paginate(10);
    }

    /**
     * Ожидающие подписания договора со стороны Автора
     *
     * @return LengthAwarePaginator
     */
    public function waitSigningAuthor(): LengthAwarePaginator
    {
        return Course::signingAuthor()->latest()->paginate(10);
    }

    /**
     * Ожидающие подписания договора со стороны Администрации
     *
     * @return LengthAwarePaginator
     */
    public function waitSigningAdmin(): LengthAwarePaginator
    {
        return Course::signingAdmin()->latest()->paginate(10);
    }
}
