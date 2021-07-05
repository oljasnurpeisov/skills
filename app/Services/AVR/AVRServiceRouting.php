<?php

namespace Services\Contracts;

use App\Models\AVR;
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
                'route_id' => $this->getFirstRoute()->id
            ]);
        } else {
            $nextRoute = $this->getNextRoute($avr->current_route->sort);

            if (!empty($nextRoute)) {
                $avr->update([
                    'route_id' => $nextRoute->id
                ]);
            } else {
                $avr->update([
                    'status' => 2
                ]);
            }
        }
    }

    /**
     * Получаем первый маршрут
     *
     * @return Route
     */
    private function getFirstRoute(): Route
    {
        return $this->route->avr()->orderBy('sort')->first();
    }

    /**
     * Получение следующего пути
     *
     * @param int $sort
     * @return Route
     */
    private function getNextRoute(int $sort)
    {
        return $this->route->avr()->where('sort', '>', $sort)->orderBy('sort')->first();
    }
}
