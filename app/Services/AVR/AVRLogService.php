<?php

namespace Services\AVR;

use App\Models\AVRLog;
use Illuminate\Support\Collection;

/**
 * Class AVRLogService
 *
 * @author kgurovoy@gmail.com
 * @package Services\Contracts
 */
class AVRLogService
{
    /**
     * @var AVRLog
     */
    private $AVRLog;

    /**
     * AVRLogService constructor.
     *
     * @param AVRLog $AVRLog
     */
    public function __construct(AVRLog $AVRLog)
    {
        $this->AVRLog = $AVRLog;
    }

    /**
     * Добавление записи в лог
     *
     * @param int $course_id
     * @param int $avr_id
     * @param int $avr_status
     * @param string|null $comment
     * @return void
     */
    public function create(int $course_id, int $avr_id, int $avr_status, string $comment = null): void
    {
        $this->AVRLog->create([
            'course_id'     => $course_id,
            'avr_id'        => $avr_id,
            'avr_status'    => $avr_status,
            'comment'       => $comment
        ]);
    }

    /**
     * Получение логов авр курса
     *
     * @param $course_id
     * @return Collection
     */
    public function getLogs($course_id): Collection
    {
        return $this->AVRLog
                ->whereCourseId($course_id)
                ->orderByDesc('created_at')
                ->latest()
                ->get();
    }
}
