<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Route\StoreRouteRole;
use App\Http\Requests\Admin\Route\UpdateRouteRole;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Service\Admin\RouteService;

/**
 * Class RoutesController
 *
 * @author kgurovoy@gmail.com
 * @package App\Http\Controllers\Admin
 */
class RoutesController extends Controller
{
    /**
     * @var RouteService
     */
    private $routeService;

    /**
     * RoutesController constructor.
     *
     * @param RouteService $routeService
     */
    public function __construct(RouteService $routeService)
    {
        $this->routeService = $routeService;
    }

    /**
     * Маршрут договора на бесплатный курс
     *
     * @return View
     */
    public function contractFree(): View
    {
        return view('admin.v2.pages.routes.index', [
            'routes'    => $this->routeService->getRoutes('contractFree'),
            'type'      => 1,
            'title'     => 'Маршрут подписания договоров (бесплатный)'
        ]);
    }

    /**
     * Маршрут договора на платный курс
     *
     * @return View
     */
    public function contractPaid(): View
    {
        return view('admin.v2.pages.routes.index', [
            'routes'    => $this->routeService->getRoutes('contractPaid'),
            'type'      => 2,
            'title'     => 'Маршрут подписания договоров (платный)'
        ]);
    }

    /**
     * Маршрут договора на курс по квоте
     *
     * @return View
     */
    public function contractQuota(): View
    {
        return view('admin.v2.pages.routes.index', [
            'routes'    => $this->routeService->getRoutes('contractQuota'),
            'type'      => 3,
            'title'     => 'Маршрут подписания договоров (по квоте)'
        ]);
    }

    /**
     * Маршрут АВР
     *
     * @return View
     */
    public function avr(): View
    {
        return view('admin.v2.pages.routes.index', [
            'routes'    => $this->routeService->getRoutes('avr'),
            'type'      => 3,
            'title'     => 'Маршрут АВР'
        ]);
    }

    /**
     * Добавление роли в маршрут
     *
     * @param Request $request
     * @return View
     */
    public function create(Request $request): View
    {
        return view('admin.v2.pages.routes.create', [
            'type'  => $request->type,
            'roles' => $this->routeService->getFreeRoles($request->type)
        ]);
    }

    /**
     * Сохранение новой роли
     *
     * @param StoreRouteRole $request
     * @return RedirectResponse
     */
    public function store(StoreRouteRole $request): RedirectResponse
    {
        $this->routeService->addRole($request->all(), $request->type);

        return redirect(route($this->routeService->typeToRouteName($request->type), ['lang'=>$request->lang]));
    }

    /**
     * Изменение роли в маршруте
     *
     * @param Request $request
     * @return View
     */
    public function edit(Request $request): View
    {
        return view('admin.v2.pages.routes.edit', [
            'type'  => $request->type,
            'route' => $this->routeService->getRole($request->route_id)
        ]);
    }

    /**
     * Обновление роли в маршруте
     *
     * @param UpdateRouteRole $request
     * @return RedirectResponse
     */
    public function update(UpdateRouteRole $request): RedirectResponse
    {
        $this->routeService->editRouteSort($request->route_id, $request->sort);

        return redirect(route($this->routeService->typeToRouteName($request->type), ['lang'=>$request->lang]));
    }

    /**
     * Удаление роли из маршрута
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        $this->routeService->removeRole($request->route_id);

        return redirect()->back();
    }
}
