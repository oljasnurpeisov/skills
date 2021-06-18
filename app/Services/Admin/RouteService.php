<?php

namespace Service\Admin;

use App\Models\Role;
use App\Models\Route;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class RouteService
 *
 * @author kgurovoy@gmail.com
 * @package Service\Admin
 */
class RouteService
{
    /**
     * @var Route
     */
    private $route;

    /**
     * RouteService constructor.
     *
     * @param Route $route
     */
    public function __construct(Route $route)
    {
        $this->route = $route;
    }

    /**
     * Get Routes
     *
     * @param null $scope
     * @return LengthAwarePaginator
     */
    public function getRoutes($scope = null): LengthAwarePaginator
    {
        $routes = $this->route->orderBy('sort');

        if (!empty($scope)) {
            $routes = $routes->$scope();
        }

        return $routes->with('role')->paginate(10);
    }

    /**
     * Получение ролей, которые можно добавить к маршруту
     *
     * @param int $route_type
     * @return mixed
     */
    public function getFreeRoles(int $route_type)
    {
        $used = $this->route->whereType($route_type)->pluck('role_id');

        return Role::whereNotIn('id', $used)->get();
    }

    /**
     * Получение роли у маршрута
     *
     * @param int $route_id
     * @return Route
     */
    public function getRole(int $route_id): Route
    {
        return $this->route->find($route_id);
    }

    /**
     * Добавление роли
     *
     * @param array $data
     * @param int $route_type
     * @return void
     */
    public function addRole(array $data, int $route_type): void
    {
        $this->route->create($data + ['type' => $route_type]);
    }

    /**
     * Изменение сортировки роли в маршруте
     *
     * @param int $route_id
     * @param int $route_sort
     */
    public function editRouteSort(int $route_id, int $route_sort)
    {
        $this->route->find($route_id)->update(['sort' => $route_sort]);
    }

    /**
     * Удаление роли из маршрута
     *
     * @param int $route_id
     * @return void
     */
    public function removeRole(int $route_id): void
    {
        $this->route->find($route_id)->delete();
    }

    /**
     * Type to route name
     *
     * @param int $route_type
     * @return string
     */
    public function typeToRouteName(int $route_type): string
    {
        switch ($route_type) {
            case 2:
                return 'admin.routes.contract_quota';
            case 1:
                return 'admin.routes.contract_paid';
            case 3:
                return 'admin.routes.avr';
            default:
                return null;
        }
    }
}
