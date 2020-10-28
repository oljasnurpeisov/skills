<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\App\PageController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

// Админ панель
Route::group(["namespace" => "Admin"], function () {
    Route::post('/ajax_upload_image', 'AjaxUploadController@ajax_upload_image');
    Route::post('/ajax_upload_file', 'AjaxUploadController@ajax_upload_file');

    Route::post('/ajaxUploadImage', 'AjaxUploadController@ajaxUploadPic');
    Route::post('/ajaxUploadFile', 'AjaxUploadController@ajaxUploadFile');
    Route::group(['prefix' => '{lang}'], function () {
        Route::group(["prefix" => "admin"], function () {
            Route::get("/login", "LoginController@showLoginForm");
            Route::post("/login", "LoginController@login");
            Route::get('/passwordReset', 'LoginController@showPasswordResetForm');
            Route::post('/passwordReset', 'LoginController@passwordReset');
            // Профиль
            Route::group(['middleware' => 'check.permission:access.panel'], static function () {
                Route::get("/", "UserController@profile");
                Route::get("/profile", "UserController@profile");
                Route::post("/profile", "UserController@profileUpdate");
            });
            // Роли
            Route::group(['middleware' => 'check.permission:admin.roles'], static function () {
                Route::get('/role/index', 'RoleController@index');
                Route::get('/role/create', 'RoleController@create');
                Route::post('/role/create', 'RoleController@store');
                Route::get('/role/{item}', 'RoleController@edit');
                Route::post('/role/{item}', 'RoleController@update');
                Route::delete('/role/{item}', 'RoleController@delete');
            });
            // Пользователи
            Route::group(['middleware' => 'check.permission:admin.users'], static function () {
                Route::get('/user/index', 'UserController@index');
                Route::get('/user/create', 'UserController@create');
                Route::post('/user/create', 'UserController@store');
                Route::get('/user/{item}/passwordUpdate', 'UserController@passwordUpdate');
                Route::get('/user/{item}', 'UserController@edit');
                Route::post('/user/{item}', 'UserController@update');
                Route::delete('/user/{item}', 'UserController@delete');
            });
            // Авторы
            Route::group(['middleware' => 'check.permission:admin.authors'], static function () {
                Route::get('/author/index', 'AuthorController@index');
                Route::get('/author/create', 'AuthorController@create');
                Route::post('/author/create', 'AuthorController@store');
                Route::get('/author/{item}/passwordUpdate', 'AuthorController@passwordUpdate');
                Route::get('/author/{item}', 'AuthorController@edit');
                Route::post('/author/{item}', 'AuthorController@update');
                Route::delete('/author/{item}', 'AuthorController@delete');
            });
            // Курсы
            Route::group(['middleware' => 'check.permission:admin.courses'], static function () {
                Route::get('/courses/index', 'CourseController@index');
                Route::get('/course/{item}', 'CourseController@view');
                Route::post('/course/publish/{item}', 'CourseController@publish');
                Route::post('/course/unpublish/{item}', 'CourseController@unpublish');
                Route::post('/course/quota_request/{item}', 'CourseController@quota_request');
                Route::post('/course/quota_contract/{item}', 'CourseController@quota_contract');
            });
        });
    });
});

Route::group(["middleware" => ["web"], "namespace" => "App"], function () {
    Route::group(['prefix' => '{lang}'], function () {
        Route::get("/", "PageController@index");
        // Профиль пользователя
        Route::get("/profile", "UserController@profile")->middleware('auth')->middleware('verified');
        Route::get("/edit_profile", "UserController@edit_profile")->middleware('auth')->middleware('verified');
        Route::post("/update_profile", "UserController@update_profile")->middleware('auth');
        Route::get("/change_password", "UserController@change_password")->middleware('auth')->middleware('verified');
        Route::post("/update_password", "UserController@update_password")->middleware('auth');
        Route::get("/profile_pay_information", "UserController@profile_pay_information")->middleware('auth')->middleware('verified');
        Route::post("/profile_pay_information", "UserController@update_profile_pay_information")->middleware('auth')->middleware('verified');
        Route::get("/profile-author-information", "UserController@author_data_show")->middleware('auth')->middleware('verified');
        Route::post("/update_author_data_profile", "UserController@update_author_data_profile")->middleware('auth')->middleware('verified');
        Route::group(['middleware' => 'check.activate'], static function () {
            Route::group(['middleware' => 'check.role:author'], static function () {
                // Мои курсы
                Route::get("/my-courses", "CourseController@myCourses")->middleware('auth')->middleware('verified');
                Route::get("/create-course", "CourseController@createCourse")->middleware('auth')->middleware('verified');
                // Курс
                Route::post("/create-course", "CourseController@storeCourse")->middleware('auth')->middleware('verified');
                Route::post("/publish-course/{item}", "CourseController@publishCourse")->middleware('auth')->middleware('verified');
                Route::post("/my-courses/edit-course/{item}", "CourseController@updateCourse")->middleware('auth')->middleware('verified');
                Route::post("/edit-course-{item}-{lesson}", "CourseController@updateLesson")->middleware('auth')->middleware('verified');
                Route::get("/my-courses/edit-course/{item}", "CourseController@editCourse")->middleware('auth')->middleware('verified');
                Route::post("/my-courses/edit-course/{item}", "CourseController@updateCourse")->middleware('auth')->middleware('verified');
                Route::post("/my-courses/delete-course/{item}", "CourseController@deleteCourse")->middleware('auth')->middleware('verified');
                Route::post("/my-courses/quota-confirm-course/{item}", "CourseController@quotaConfirm")->middleware('auth')->middleware('verified');

                // Тема
                Route::post("/create-theme", "ThemeController@createTheme")->middleware('auth')->middleware('verified');
                Route::post("/edit-theme/{item}", "ThemeController@editTheme")->middleware('auth')->middleware('verified');
                Route::delete("/delete-theme/{item}", "ThemeController@deleteTheme")->middleware('auth')->middleware('verified');
                Route::post("/moveup-theme/{item}", "ThemeController@moveupTheme")->middleware('auth')->middleware('verified');
                Route::post("/movedown-theme/{item}", "ThemeController@movedownTheme")->middleware('auth')->middleware('verified');
                // Урок
                Route::get("/my-courses/course/{item}/theme-{theme}/create-lesson", "LessonController@createLesson")->middleware('auth')->middleware('verified');
                Route::get("/my-courses/theme-{theme}/edit-lesson-{lesson}", "LessonController@editLesson")->middleware('auth')->middleware('verified');
                Route::post("/create-lesson", "LessonController@storeLesson")->middleware('auth')->middleware('verified');
                Route::post("/edit-lesson-{item}", "LessonController@updateLesson")->middleware('auth')->middleware('verified');
                Route::delete("/delete-lesson/{item}", "LessonController@deleteLesson")->middleware('auth')->middleware('verified');
                Route::post("/moveup-lesson/{item}", "LessonController@moveupLesson")->middleware('auth')->middleware('verified');
                Route::post("/movedown-lesson/{item}", "LessonController@movedownLesson")->middleware('auth')->middleware('verified');
                // Черновики
                Route::get("/my-courses/drafts", "CourseController@myDrafts")->middleware('auth')->middleware('verified');
                // Неопубликованные курсы
                Route::get("/my-courses/unpublished", "CourseController@myUnpublishedCourses")->middleware('auth')->middleware('verified');
                // На проверке
                Route::get("/my-courses/on-check", "CourseController@myOnCheckCourses")->middleware('auth')->middleware('verified');
                // Удаленные курсы
                Route::get("/my-courses/deleted", "CourseController@myDeletedCourses")->middleware('auth')->middleware('verified');
                // Редактирование курса
                Route::get("/my-courses/course/{item}", "CourseController@courseShow")->middleware('auth')->middleware('verified');
            });
        });
    });
    Route::get("/", "PageController@index");
});

Route::group(["middleware" => ["web"], "namespace" => "Auth"], function () {
    Route::group(['prefix' => '{lang}'], function () {
        //Сброс пароля
        Route::post("/forgot_password", "ForgotPasswordController@sendNewPassword");
        Route::get("/forgot_password", "ForgotPasswordController@forgotIndex");
    });
});

Route::group(['prefix' => '{lang}'], function () {
    Auth::routes();
    Auth::routes(['reset' => false]);

    Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::get('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');
Route::post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');
//Auth::routes();


