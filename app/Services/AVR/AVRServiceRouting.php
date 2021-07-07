<?php

namespace Services\Contracts;

use App\Models\AVR;
use App\Models\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
     * @var $AVRService
     */
    private $AVRService;

    /**
     * ContractServiceRouting constructor.
     *
     * @param Route $route
     * @param AVRService $AVRService
     */
    public function __construct(Route $route, AVRService $AVRService)
    {
        $this->route = $route;
        $this->AVRService = $AVRService;
    }

    /**
     * Маршрутизация акта
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

                try {

                    DB::beginTransaction();

                    if ($avr->update([
                        'status' => 2
                    ])) {

                        if ($avr->document && $avr->document->lastSignature) {
                            $avr->signed_at = $avr->document->lastSignature->created_at;
                            $avr->link = asset($this->AVRService->avrToPdf($avr->id));
                            $avr->save();
                        }

                        DB::commit();
                    }

                } catch (\Exception $exception) {
                    DB::rollBack();
                    Log::error($exception);
                }
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
