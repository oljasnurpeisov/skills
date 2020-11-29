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
    Route::post('/ajaxUploadFilesTest', 'AjaxUploadController@ajaxUploadFilesTest');
});

Route::group(["middleware" => ["web"], "namespace" => "Admin"], function () {
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
                Route::get("/courses/drafts", "CourseController@drafts_index");
                Route::get("/courses/deleted", "CourseController@deleted_index");
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

// Таблица с уроками и темами
Route::group(["middleware" => ["web"], "namespace" => "App"], function () {
    Route::group(['middleware' => ["auth"]], static function () {
        Route::group(['middleware' => 'check.role:author'], static function () {
            Route::group(["namespace" => "Author"], function () {
                // Получить курс
                Route::get("/getCourseData/{course}", "CourseController@getCourseData");
                // Тема
                Route::post("/create-theme", "ThemeController@createTheme");
                Route::post("/edit-theme", "ThemeController@editTheme");
                Route::delete("/delete-theme", "ThemeController@deleteTheme");
                Route::post("/move-theme", "ThemeController@moveTheme");
                // Урок
                Route::delete("/delete-lesson", "LessonController@deleteLesson");
                Route::post("/move-lesson", "LessonController@moveLesson");
            });
        });
    });

    Route::group(["middleware" => ["web"], "namespace" => "General"], function () {
        Route::get("/", "PageController@index");
        // Оплата курса
        Route::post("/createPaymentOrder/{item}", "PaymentController@createPaymentOrder");
        Route::post("/callbackPaymentOrder", "PaymentController@callbackPaymentOrder");
    });
    Route::group(["middleware" => ["web"], "namespace" => "Author"], function () {
        // Сохранение изображения автора
        Route::post('/ajax_upload_image', 'AjaxUploadController@ajax_upload_image');
        // Сохранение изображения компании
        Route::post('/ajax_upload_company_image', 'AjaxUploadController@ajaxUploadCompanyImage');
        // Сохранение изображений теста
        Route::post('/ajax_upload_test_images', 'AjaxUploadController@ajaxUploadTestImages');
        // Сохранение сертификатов автора
        Route::post('/ajax_upload_certificates', 'AjaxUploadController@ajaxUploadCertificates');
        // Сохранение изображения курса
        Route::post('/ajax_upload_course_image', 'AjaxUploadController@ajaxUploadCourseImage');
        // Сохранение изображения урока
        Route::post('/ajax_upload_lesson_image', 'AjaxUploadController@ajaxUploadLessonImage');
        // Сохранение видео курса
        Route::post('/ajax_upload_course_videos', 'AjaxUploadController@ajaxUploadCourseVideos');
        // Сохранение аудио курса
        Route::post('/ajax_upload_course_audios', 'AjaxUploadController@ajaxUploadCourseAudios');
        // Сохранение видео урока
        Route::post('/ajax_upload_lesson_videos', 'AjaxUploadController@ajaxUploadLessonVideos');
        // Сохранение аудио урока
        Route::post('/ajax_upload_lesson_audios', 'AjaxUploadController@ajaxUploadLessonAudios');
        // Сохранение других материалов урока
        Route::post('/ajax_upload_lesson_another_files', 'AjaxUploadController@ajaxUploadLessonAnotherFiles');

    });

    Route::group(['prefix' => '{lang}'], function () {
        Route::group(["middleware" => ["web"], "namespace" => "General"], function () {
            Route::get("/", "PageController@index");
            // Курсы
            Route::get("/course-catalog", "CourseController@courseCatalog");
            Route::get("/course-catalog/course/{item}", "CourseController@courseView");
            // Фильтр
            Route::post("/course-catalog-filter", "CourseController@courseCatalogFilter");

            Route::post("/getProfessionsByName", "CourseController@getProfessionsByName");
            Route::post("/getSkillsByData", "CourseController@getSkillsByData");
            Route::post("/getSkills", "CourseController@getSkills");
            Route::post("/getAuthorsByName", "CourseController@getAuthorsByName");

            Route::group(['middleware' => ["auth"]], static function () {
                // Диалоги
                Route::get("/dialogs", "DialogController@index");
                Route::get('/dialog/opponent-{id}', 'DialogController@view');
                Route::post('/dialog-{dialog}/message/create', 'DialogController@save');
                // Уведомления
                Route::get("/notifications", "PageController@notifications");
            });

        });
        Route::group(["middleware" => ["web"], "namespace" => "Student"], function () {
            Route::get("/login_student", "UserController@studentAuth");
            Route::post("/login_student", "UserController@studentLogin");

        });
        Route::group(['middleware' => ["auth"]], static function () {
            Route::group(['middleware' => 'check.role:author'], static function () {
                Route::group(["middleware" => ["web"], "namespace" => "Author"], function () {
                    // Профиль автора
                    Route::get("/profile", "UserController@profile");
                    Route::get("/edit-profile", "UserController@edit_profile");
                    Route::post("/update_profile", "UserController@update_profile");
                    Route::get("/change-password", "UserController@change_password");
                    Route::post("/update_password", "UserController@update_password");
                    Route::get("/profile-pay-information", "UserController@profile_pay_information");
                    Route::post("/profile_pay_information", "UserController@update_profile_pay_information");
                    Route::get("/profile-author-information", "UserController@author_data_show");
                    Route::post("/update_author_data_profile", "UserController@update_author_data_profile");
                });
            });
//            Route::group(['middleware' => 'check.activate'], static function () {
            Route::group(['middleware' => 'check.role:student'], static function () {
                Route::group(["middleware" => ["web"], "namespace" => "Student"], function () {
                    // Профиль обучающегося
                    Route::get("/student-profile", "UserController@student_profile");
                    Route::post("/update_student_profile", "UserController@update_student_profile");
                    // Курсы
                    Route::get("/student/my-courses", "CourseController@studentCourses");
                    Route::post("/course-{course}/saveCourseRate", "CourseController@saveCourseRate");
                    // Урок
                    Route::get("/course-catalog/course/{course}/theme-{theme}/lesson-{lesson}", "LessonController@lessonView");
                    Route::post("/course_{course}/theme-{theme}/student_lesson_finished_{lesson}", "LessonController@lessonFinished");
                    // Домашняя и Курсовая работа
                    Route::get("/course-catalog/course/{course}/theme-{theme}/lesson-{lesson}/homework", "LessonController@homeworkView");
                    Route::get("/course-catalog/course/{course}/theme-{theme}/lesson-{lesson}/coursework", "LessonController@courseworkView");
                    Route::post("/course-{course}/theme-{theme}/lesson-{lesson}/textwork", "LessonController@answerSend");
                });
            });
            Route::group(['middleware' => 'check.role:author'], static function () {
                Route::group(["middleware" => ["web"], "namespace" => "Author"], function () {
                    // Мои курсы
                    Route::get("/my-courses", "CourseController@myCourses");
                    Route::get("/create-course", "CourseController@createCourse");
                    Route::get("/my-courses/statistics", "CourseController@statisticsCourse");
                    Route::get("/my-courses/reporting", "CourseController@reportingCourse");
                    Route::get("/export-reporting", "CourseController@exportReporting");

                    Route::get("/my-courses/statistics/statisticForChart", "CourseController@statisticForChart");

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
                    // Урок
                    Route::get("/my-courses/course/{item}/theme-{theme}/create-lesson", "LessonController@createLesson");
                    Route::get("/my-courses/course/{course}/edit-lesson-{lesson}", "LessonController@editLesson");
//                    Route::get("/my-courses/course/{course}/theme-{theme}/view-lesson-{lesson}", "LessonController@viewLesson");
                    Route::post("/create-lesson/{course}/{theme}", "LessonController@storeLesson");
                    Route::post("/course-{course}/edit-lesson-{item}", "LessonController@updateLesson");
                    Route::delete("/course-{course}/theme-{theme}/lesson-{lesson}/delete-lesson-form", "LessonController@deleteLessonForm");
                    Route::delete("/course-{course}/lesson-{lesson}/deleteTest", "LessonController@deleteTest");

                    // Домашняя, Курсовая работа и Тест для Автора
                    Route::get("/my-courses/course/{course}/view-lesson-{lesson}", "LessonController@viewLesson");
                    Route::get("/course-catalog/course/{course}/lesson-{lesson}/author-test", "LessonController@testView");
                    Route::get("/course-catalog/course/{course}/lesson-{lesson}/author-homework", "LessonController@homeWorkView");
                    Route::get("/course-catalog/course/{course}/lesson-{lesson}/author-coursework", "LessonController@homeWorkView");
                    Route::post("/course-{course}/theme-{theme}/lesson-{lesson}/textwork", "LessonController@answerSend");
                    Route::post("/course-{course}/lesson-{lesson}/author-test-submit", "LessonController@submitTest");
                    Route::post("/course-{course}/lesson-{lesson}/author-homework-submit", "LessonController@submitHomeWork");
                    Route::post("/course_{course}/author_lesson_finished_{lesson}", "LessonController@lessonFinished");
                    // Курсовая работа
                    Route::get("/my-courses/course/{item}/create-coursework", "LessonController@createCoursework");
                    Route::post("/course-{course}/create-coursework", "LessonController@storeCoursework");
                    Route::get("/my-courses/course/{course}/edit-coursework", "LessonController@editCoursework");
                    Route::post("/my-courses/course/{course}/edit-coursework", "LessonController@updateCoursework");
                    // Финальное тестирование
                    Route::get("/my-courses/course/{item}/create-final-test", "LessonController@createFinalTest");
                    Route::post("/my-courses/course/{course}/create-final-test", "LessonController@storeFinalTest");
                    Route::get("/my-courses/course/{course}/edit-final-test", "LessonController@editFinalTest");
                    Route::post("/my-courses/course/{course}/edit-final-test", "LessonController@updateFinalTest");
                    // Черновики
                    Route::get("/my-courses/drafts", "CourseController@myCourses");
                    // Неопубликованные курсы
                    Route::get("/my-courses/unpublished", "CourseController@myCourses");
                    // На проверке
                    Route::get("/my-courses/on-check", "CourseController@myCourses");
                    // Удаленные курсы
                    Route::get("/my-courses/deleted", "CourseController@myCourses");
                    // Редактирование курса
                    Route::get("/my-courses/course/{item}", "CourseController@courseShow");
                });

            });
        });
    });
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


