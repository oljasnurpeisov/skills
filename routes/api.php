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
        // Навыки обучающегося по ИИН
        APIRoute::get("/skills-by-iin", "ServiceController@getSkillsByIin");
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
        // Добавление ФИО и ИИН
        APIRoute::post("/save-resume-data", "UserController@saveStudentResumeData");
        // Редактирование профиля
        APIRoute::post("/upload-avatar", "UserController@uploadAvatar");
        // Уведомления
        APIRoute::get("/notifications", "UserController@getNotifications");
        // Диалоги
        APIRoute::get("/dialogs", "UserController@getDialogs");
        // Сообщения
        APIRoute::get("/dialog", "UserController@getDialog");
        // Создать сообщение
        APIRoute::post("/dialog/create-message", "UserController@saveMessage");
        // Получить диалог
        APIRoute::get("/dialog-by-opponent", "UserController@getDialogByOpponent");
        // Сертификаты
        APIRoute::get("/certificates", "UserController@getCertificates");
        // Сохранить токен для push-уведомлений
        APIRoute::put("/update-token", "UserController@updateToken");
        // Включить/отключить push-уведомления
        APIRoute::put("/update-push-status", "UserController@updatePushStatus");
    });
    APIRoute::group(["prefix" => "courses"], function () {
        // Авторы
        APIRoute::get("/authors", "CourseController@getAuthors");
        // Профессии
        APIRoute::get("/professions", "CourseController@getProfessions");
        // Навыки из профессий
        APIRoute::get("/skills", "CourseController@getSkills");
        // Оставить отзыв
        APIRoute::post("/course-rate", "CourseController@courseRate");
        // Каталог
        APIRoute::get("/catalog", "CourseController@catalogFilter");
        // Курс
        APIRoute::get("/view", "CourseController@courseView");
    });
    APIRoute::group(["prefix" => "lessons"], function () {
        // Урок
        APIRoute::get("/view", "LessonController@lessonView");
        // Завершить урок
        APIRoute::post("/finish-lesson", "LessonController@lessonFinish");
        // Отправить домашнюю/курсовую работу
        APIRoute::post("/send-coursework", "LessonController@sendHomeWork");
        // Отправить тест
        APIRoute::post("/send-test", "LessonController@sendTest");
    });
});
