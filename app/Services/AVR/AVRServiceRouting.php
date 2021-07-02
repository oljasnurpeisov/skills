<?php

namespace Services\Contracts;

use App\Models\AVR;
use App\Models\Contract;
use App\Models\Route;

/**
 * Class AVRServiceRouting
 *
 * @author kgurovoy@gmail.com
 * @package Services\Contracts
 */
class AVRServiceRouting
{
    /**
     * @var Route
     */
    private $route;

    /**
     * ContractServiceRouting constructor.
     *
     * @param Route $route
     */
    public function __construct(Route $route)
    {
        $this->route = $route;
    }

    /**
     * Маршрутизация контракта
     *
     * @param AVR $avr
     */
    public function toNextRoute(AVR $avr)
    {
        if (empty($avr->current_route))
        {
            $avr->update([
                'route_id' => $this->getFirstRoute($contract->type)->id
            ]);
        } else {
            $nextRoute = $this->getNextRoute($contract->type, $contract->current_route->sort);

            if (!empty($nextRoute)) {
                $contract->update([
                    'route_id' => $nextRoute->id
                ]);
            } else {
                $contract->update([
                    'status' => 2
                ]);

                if ($contract->isQuota()) {
                    $contract->course()->update([
                        'quota_status' => 2
                    ]);
                }
            }
        }
    }

    /**
     * Получаем первый маршрут
     *
     * @param int $course_type
     * @return Route
     */
    private function getFirstRoute(int $course_type): Route
    {
        return $this->route->whereType($course_type)->orderBy('sort')->first();
    }

    /**
     * Получение следующего пути
     *
     * @param int $course_type
     * @param int $sort
     * @return Route
     */
    private function getNextRoute(int $course_type, int $sort)
    {
        return $this->route->whereType($course_type)->where('sort', '>', $sort)->orderBy('sort')->first();
    }
}