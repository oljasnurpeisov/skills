<?php

use App\Http\Middleware\CORS;
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


// Админ панель
Route::group(["namespace" => "Admin"], function () {
    Route::post('/ajax_upload_image', 'AjaxUploadController@ajax_upload_image');
    Route::post('/ajax_upload_file', 'AjaxUploadController@ajax_upload_file');

    Route::post('/ajaxUploadImage', 'AjaxUploadController@ajaxUploadPic');
    Route::post('/ajaxUploadFile', 'AjaxUploadController@ajaxUploadFile');

    // Тест для Айтана
    Route::post('/ajaxUploadImageTest', 'AjaxUploadController@ajaxUploadPicTest');


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
                Route::get('/courses/wait_verification', 'CourseController@wait_verification');
                Route::get('/courses/unpublished', 'CourseController@unpublished_index');
                Route::get('/courses/published', 'CourseController@published_index');
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
    Route::group(["middleware" => ["web"], "namespace" => "General"], function () {
        Route::get("/", "PageController@index");
        // Оплата курса
        Route::post("/createPaymentOrder/{item}", "PaymentController@createPaymentOrder");
        Route::post("/callbackPaymentOrder", "PaymentController@callbackPaymentOrder");
    });

    Route::group(['prefix' => '{lang}'], function () {

        Route::group(["middleware" => ["web"], "namespace" => "General"], function () {
            Route::get("/", "PageController@index");
            // Каталог курсов
            Route::get("/course-catalog", "PageController@courseCatalog");
            Route::get("/course-catalog/course/{item}", "PageController@courseView");

            Route::post("/course-catalog-filter", "PageController@courseCatalogFilter");
        });
        Route::group(["middleware" => ["web"], "namespace" => "Student"], function () {
            Route::get("/login_student", "UserController@studentAuth");
            Route::post("/login_student", "UserController@studentLogin");
            // Оплата курса
//            Route::post("/createPaymentOrder/{item}", "PaymentController@createPaymentOrder");
//            Route::post("/callbackPaymentOrder", "PaymentController@callbackPaymentOrder");
        });
        Route::group(['middleware' => ["auth", "verified"]], static function () {
            Route::group(['middleware' => 'check.role:author'], static function () {
                Route::group(["middleware" => ["web"], "namespace" => "Author"], function () {
                    // Профиль автора
                    Route::get("/profile", "UserController@profile");
                    Route::get("/edit_profile", "UserController@edit_profile");
                    Route::post("/update_profile", "UserController@update_profile");
                    Route::get("/change_password", "UserController@change_password");
                    Route::post("/update_password", "UserController@update_password");
                    Route::get("/profile_pay_information", "UserController@profile_pay_information");
                    Route::post("/profile_pay_information", "UserController@update_profile_pay_information");
                    Route::get("/profile-author-information", "UserController@author_data_show");
                    Route::post("/update_author_data_profile", "UserController@update_author_data_profile");
                });
            });
            Route::group(['middleware' => 'check.activate'], static function () {
                Route::group(['middleware' => 'check.role:student'], static function () {
                    Route::group(["middleware" => ["web"], "namespace" => "Student"], function () {
                        // Профиль обучающегося
                        Route::get("/student-profile", "UserController@student_profile");
                        Route::post("/update_student_profile", "UserController@update_student_profile");
                    });
                });
                Route::group(['middleware' => 'check.role:author'], static function () {
                    Route::group(["middleware" => ["web"], "namespace" => "Author"], function () {
                        // Мои курсы
                        Route::get("/my-courses", "CourseController@myCourses");
                        Route::get("/create-course", "CourseController@createCourse");
                        // Курс
                        Route::post("/create-course", "CourseController@storeCourse");
                        Route::post("/publish-course/{item}", "CourseController@publishCourse");
                        Route::post("/my-courses/edit-course/{item}", "CourseController@updateCourse");
                        Route::post("/edit-course-{item}-{lesson}", "CourseController@updateLesson");
                        Route::get("/my-courses/edit-course/{item}", "CourseController@editCourse");
                        Route::post("/my-courses/edit-course/{item}", "CourseController@updateCourse");
                        Route::post("/my-courses/delete-course/{item}", "CourseController@deleteCourse");
                        Route::post("/my-courses/reestablish-course/{item}", "CourseController@reestablishCourse");
                        Route::post("/my-courses/quota-confirm-course/{item}", "CourseController@quotaConfirm");
                        // Тема
                        Route::post("/create-theme", "ThemeController@createTheme");
                        Route::post("/edit-theme/{item}", "ThemeController@editTheme");
                        Route::delete("/delete-theme/{item}", "ThemeController@deleteTheme");
                        Route::post("/moveup-theme/{item}", "ThemeController@moveupTheme");
                        Route::post("/movedown-theme/{item}", "ThemeController@movedownTheme");
                        // Урок
                        Route::get("/my-courses/course/{item}/theme-{theme}/create-lesson", "LessonController@createLesson");
                        Route::get("/my-courses/theme-{theme}/edit-lesson-{lesson}", "LessonController@editLesson");
                        Route::post("/create-lesson", "LessonController@storeLesson");
                        Route::post("/edit-lesson-{item}", "LessonController@updateLesson");
                        Route::delete("/delete-lesson/{item}", "LessonController@deleteLesson");
                        Route::post("/moveup-lesson/{item}", "LessonController@moveupLesson");
                        Route::post("/movedown-lesson/{item}", "LessonController@movedownLesson");
                        // Черновики
                        Route::get("/my-courses/drafts", "CourseController@myDrafts");
                        // Неопубликованные курсы
                        Route::get("/my-courses/unpublished", "CourseController@myUnpublishedCourses");
                        // На проверке
                        Route::get("/my-courses/on-check", "CourseController@myOnCheckCourses");
                        // Удаленные курсы
                        Route::get("/my-courses/deleted", "CourseController@myDeletedCourses");
                        // Редактирование курса
                        Route::get("/my-courses/course/{item}", "CourseController@courseShow");
                    });

                });
            });
        });
    });
//    Route::get("/", "PageController@index");
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


