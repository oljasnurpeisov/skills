<?php

namespace Services\Contracts;

use App\Models\Contract;
use App\Models\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
     * @var ContractService
     */
    private $contactService;

    /**
     * ContractServiceRouting constructor.
     *
     * @param Route $route
     */
    public function __construct(Route $route, ContractService $contractService)
    {
        $this->route = $route;
        $this->contactService = $contractService;
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
                    'route_id' => $nextRoute->id
                ]);
            } else {

                try {

                    DB::beginTransaction();

                    if ($contract->update([
                        'status' => 2
                    ])) {

                        if ($contract->document && $contract->document->lastSignature) {
                            $contract->signed_at = $contract->document->lastSignature->created_at;
                            $contract->link = asset($this->contactService->contractToPdf($contract->id));
                            $contract->save();
                        }
                    }

                    if ($contract->isQuota()) {
                        $contract->course()->update([
                            'quota_status' => 2
                        ]);
                    }

                    DB::commit();

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
