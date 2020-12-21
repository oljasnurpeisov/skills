<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

APIRoute::version('v1', ['prefix' => 'api/service', 'namespace' => 'App\Api\V1\Controllers\Service'], function () {
    APIRoute::group(["prefix" => "user"], function () {
        // Навыки обучающегося
        APIRoute::get("/skills", "ServiceController@getSkillsByUid");
        // Обновить навыки
        APIRoute::put("/skills-update", "ServiceController@updateSkillsByUid");
        // Обновить количество квот
        APIRoute::put("/quota-update", "ServiceController@updateQuotaByUid");
    });
    // Результат поиска
    APIRoute::get("/search/courses", "ServiceController@getSearchResult");
});

APIRoute::version('v1', ['prefix' => 'api/app', 'namespace' => 'App\Api\V1\Controllers\App'], function () {
    APIRoute::group(["prefix" => "user"], function () {
        // Авторизация обучающегося
        APIRoute::post("/login", "UserController@studentLogin");
        // Редактирование профиля
        APIRoute::post("/upload-avatar", "UserController@uploadAvatar");
    });
});
