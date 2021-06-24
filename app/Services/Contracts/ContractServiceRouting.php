<?php

namespace Services\Contracts;


use App\Models\Contract;
use App\Models\Route;

/**
 * Class ContractServiceRouting
 *
 * @author kgurovoy@gmail.com
 * @package Services\Contracts
 */
class ContractServiceRouting
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
     * @param Contract $contract
     */
    public function toNextRoute(Contract $contract)
    {
        if (empty($contract->current_route))
        {
            $contract->update([
                'route_id' => $this->getFirstRoute($contract->type)->id
            ]);
        } else {
            $nextRoute = $this->getNextRoute($contract->type, $contract->current_route->sort);

            if (!empty($nextRoute)) {
                $contract->update([
                    'route_id' => $this->getNextRoute($contract->type, $contract->current_route->sort)->id
                ]);
            } else {
                // меняем статус на подписано
            }
        }
    }

    /**
     * Получаем первый маршрут
     *
     * @param int $course_type
     * @return Route
     */
    public function getFirstRoute(int $course_type): Route
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
    public function getNextRoute(int $course_type, int $sort)
    {
        return $this->route->whereType($course_type)->where('sort', '>', $sort)->orderBy('sort')->first();
    }
}
