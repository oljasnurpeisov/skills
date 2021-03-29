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
    Route::post('/ajaxUploadImageContent', 'AjaxUploadController@ajaxUploadPicContent');
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
                Route::get('/user/index_all', 'UserController@index_all');
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
            // Обучающиеся
            Route::group(['middleware' => 'check.permission:admin.users'], static function () {
                Route::get('/student/index', 'StudentController@index');
                Route::get('/student/{item}', 'StudentController@edit');
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
                // Предосмотр курса
                Route::post("/course_{course}/admin_lesson_finished_{lesson}", "PreviewCourseController@lessonFinished");
                Route::get('/moderator-course-iframe-{item}', 'PreviewCourseController@viewCourse');
                Route::get('/moderator-course-iframe-{item}/lesson-{lesson}', 'PreviewCourseController@viewLesson');
                Route::get("/course-catalog/course/{course}/lesson-{lesson}/admin-homework", "PreviewCourseController@homeWorkView");
                Route::get("/course-catalog/course/{course}/lesson-{lesson}/admin-coursework", "PreviewCourseController@homeWorkView");
                Route::post("/course-{course}/lesson-{lesson}/admin-homework-submit", "PreviewCourseController@submitHomeWork");
                Route::get("/course-catalog/course/{course}/lesson-{lesson}/admin-test", "PreviewCourseController@testView");
                Route::post("/course-{course}/lesson-{lesson}/admin-test-submit", "PreviewCourseController@submitTest");

            });
            // Страницы
            Route::group(['middleware' => 'check.permission:admin.pages'], static function () {
                // Главная
                Route::get('/static-pages/main', 'PageController@main');
                Route::post("/static-pages/main-update", "PageController@mainUpdate");
                // Для авторов
                Route::get('/static-pages/for-authors', 'PageController@forAuthors');
                Route::post("/static-pages/for-authors-update", "PageController@forAuthorsUpdate");
                // FAQ
                Route::get('/static-pages/faq-index', 'PageController@faq_index');
                Route::get('/static-pages/faq-create', 'PageController@create_faq_theme');
                Route::post('/static-pages/store-faq-view/{item}', 'PageController@store_faq_theme');
                Route::get('/static-pages/faq-view/{key}', 'PageController@faq_view');
                Route::post('/static-pages/update-faq-view/{item}/{key}', 'PageController@update_faq_theme');
                Route::get('/static-pages/faq', 'PageController@faq');
                Route::delete('/static-pages/delete-faq-theme/{key}', 'PageController@faq_delete_theme');
                // Помощь
                Route::get('/static-pages/help-index', 'PageController@help_index');
                Route::get('/static-pages/help-create', 'PageController@create_help_theme');
                Route::post('/static-pages/store-help-view/{item}', 'PageController@store_help_theme');
                Route::get('/static-pages/help-view/{key}', 'PageController@help_view');
                Route::post('/static-pages/update-help-view/{item}/{key}', 'PageController@update_help_theme');
                Route::delete('/static-pages/delete-help-theme/{key}', 'PageController@help_delete_theme');
                // Калькулятор
                Route::get('/static-pages/calculator', 'PageController@calculator_view');
                Route::post("/static-pages/calculator", "PageController@calculator_update");
                // Каталог курсов
                Route::get('/static-pages/course-catalog', 'PageController@courseCatalog');
                Route::post("/static-pages/course-catalog-update", "PageController@courseCatalogUpdate");
            });
            // Диалоги
            Route::group(['middleware' => 'check.permission:admin.tech_support'], static function () {
                Route::get('/dialogs', 'DialogController@main');
                Route::get('/dialogs/opponent-{id}', 'DialogController@view');
                Route::get('/dialogs/dialog-iframe-{id}', 'DialogController@viewDialog');
                Route::post('/dialog-{dialog}/message/create', 'DialogController@save');
            });
            // Конфиги php
            Route::group(['middleware' => 'check.permission:admin.tech_support'], static function () {
                Route::get('/phpinfo', 'PageController@phpInfo');
            });
            // Отчеты
            Route::group(['middleware' => 'check.permission:admin.reports'], static function () {
                // Отчеты по авторам
                Route::get('/reports/authors', 'ReportController@authorsReports');
                Route::get("/export-authors-report", "ReportController@exportAuthorsReport");
                // Отчеты по курсам
                Route::get('/reports/courses', 'ReportController@coursesReports');
                Route::get("/export-courses-report", "ReportController@exportCoursesReport");
                // Отчеты по обучающимся
                Route::get('/reports/students', 'ReportController@studentsReports');
                Route::get("/export-students-report", "ReportController@exportStudentsReport");
                // Отчеты по сертификатам
                Route::get('/reports/certificates', 'ReportController@certificatesReports');
                Route::get("/export-certificates-report", "ReportController@exportCertificates");
            });
        });
    });
});


// Таблица с уроками и темами
Route::group(["middleware" => ["web"], "namespace" => "App"], function () {
    Route::group(['middleware' => ["auth"]], static function () {
        Route::group(['middleware' => 'check.role:author'], static function () {
            Route::group(["namespace" => "Author"], function () {
                // Тема
                Route::post("/create-theme", "ThemeController@createTheme");
                Route::post("/edit-theme", "ThemeController@editTheme");
                Route::delete("/delete-theme", "ThemeController@deleteTheme");
                Route::post("/move-theme", "ThemeController@moveTheme");
                // Урок
                Route::delete("/delete-lesson", "LessonController@deleteLesson");
                Route::post("/move-lesson", "LessonController@moveLesson");
                // Урок без темы
                Route::post("/move-item", "ThemeController@moveItem");
            });
        });
    });
    // Оплата курса
    Route::group(["middleware" => ["web"], "namespace" => "General"], function () {
        Route::get("/", "PageController@index");
        // Оплата курса
        Route::post("/createPaymentOrder/{item}", "PaymentController@createPaymentOrder");
        Route::post("/callbackPaymentOrder", "PaymentController@callbackPaymentOrder");
    });
    //
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
        Route::post('/ajax_upload_lesson_another_file', 'AjaxUploadController@ajaxUploadLessonAnotherFile');

    });
    //
    Route::group(['prefix' => '{lang}'], function () {
        Route::group(["middleware" => ["web"], "namespace" => "General"], function () {
            Route::get("/", "PageController@index");
            // Для авторов
            Route::get("/for-authors", "PageController@for_authors");
            // FAQ
            Route::get("/faq", "PageController@faq");
            // Помощь
            Route::get("/help", "PageController@help");
            // Курсы
            Route::get("/course-catalog", "CourseController@courseCatalog");
            Route::get("/course-catalog/course/{item}", "CourseController@courseView");
            // Фильтр
            Route::post("/course-catalog-filter", "CourseController@courseCatalogFilter");

            Route::get("/getProfessionsByName", "CourseController@getProfessionsByName");
            Route::get("/getProfessionalAreaByName", "CourseController@getProfessionalAreaByName");
            Route::get("/getSkillsByData", "CourseController@getSkillsByData");
            Route::get("/getSkills", "CourseController@getSkills");
            Route::get("/getAuthorsByName", "CourseController@getAuthorsByName");
            //
            Route::get("/getSkills", "CourseController@getCourseSkills");
            Route::get("/getProfessionsBySkills/{skill_id}", "CourseController@getProfessionsBySkills");
            Route::get("/getProfessionsByProfessionalArea/{professional_area_id}", "CourseController@getProfessionsByProfessionalArea");
            Route::get("/getProfessionsByData", "CourseController@getProfessionsByData");
            Route::get("/getSkillsByProfession/{profession_id}", "CourseController@getSkillsByProfession");
            //
            Route::post("/markAsReadNotifications", "CourseController@markAsReadNotifications");

            Route::group(['middleware' => ["auth"]], static function () {
                // Диалоги
                Route::get("/dialogs", "DialogController@index");
                Route::get('/dialog/opponent-{id}', 'DialogController@view');
                Route::post('/dialog-{dialog}/message/create', 'DialogController@save');
                // Уведомления
                Route::get("/notifications", "PageController@notifications");
            });

        });
        //
        Route::group(["middleware" => ["web"], "namespace" => "Student"], function () {
            Route::get("/login_student", "UserController@studentAuth");
            Route::post("/login_student", "UserController@studentLogin");
            Route::post("/save-student-data/{user_id}", "UserController@studentDataSave");
        });
        //
        Route::group(['middleware' => ["auth"]], static function () {
            Route::group(['middleware' => 'check.role:author'], static function () {
                Route::group(["middleware" => ["web"], "namespace" => "Author"], function () {
                    // Профиль автора
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
            //
            Route::group(['middleware' => 'check.role:student'], static function () {
                Route::group(["middleware" => ["web"], "namespace" => "Student"], function () {
                    // Профиль обучающегося
                    Route::get("/student-profile", "UserController@student_profile");
                    Route::post("/update_student_profile", "UserController@update_student_profile");
                    // Сертификаты
                    Route::get("/student/my-certificates", "UserController@myCertificates");
                    // Курсы
                    Route::get("/student/my-courses", "CourseController@studentCourses");
                    Route::post("/course-{course}/saveCourseRate", "CourseController@saveCourseRate");
                    // Урок
                    Route::get("/course-catalog/course/{course}/lesson-{lesson}", "LessonController@lessonView");
                    Route::post("/course_{course}/student_lesson_finished_{lesson}", "LessonController@lessonFinished");
                    // Задания
                    Route::get("/course-catalog/course/{course}/lesson-{lesson}/homework", "LessonController@homeworkView");
                    Route::get("/course-catalog/course/{course}/lesson-{lesson}/test", "LessonController@testView");
                    Route::get("/course-catalog/course/{course}/lesson-{lesson}/test-result", "LessonController@testResultView");

                    Route::post("/course-{course}/lesson-{lesson}/answerSend", "LessonController@answerSend");

                });
            });
            //
            Route::group(['middleware' => 'check.role:author'], static function () {
                Route::group(["middleware" => ["web"], "namespace" => "Author"], function () {
                    // Получить курс
                    Route::get("/getCourseData/{course}", "CourseController@getCourseData");
                    // Мои курсы
                    Route::get("/my-courses", "CourseController@myCourses");
                    Route::get("/create-course", "CourseController@createCourse");
                    Route::get("/my-courses/statistics", "CourseController@statisticsCourse");
                    Route::get("/my-courses/reporting", "CourseController@reportingCourse");
                    Route::get("/export-reporting", "CourseController@exportReporting");
                    // Статистика для графика
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
                    Route::get("/my-courses/course/{item}/create-lesson", "LessonController@createUnthemeLesson");
                    Route::get("/my-courses/course/{course}/edit-lesson-{lesson}", "LessonController@editLesson");
                    Route::get("/my-courses/course/{course}/edit-untheme-lesson-{lesson}", "LessonController@editUnthemeLesson");
                    Route::post("/create-lesson/{course}", "LessonController@storeUnthemeLesson");
                    Route::post("/create-lesson/{course}/{theme}", "LessonController@storeLesson");
                    Route::post("/course-{course}/edit-lesson-{item}", "LessonController@updateLesson");
                    Route::delete("/course-{course}/theme-{theme}/lesson-{lesson}/delete-lesson-form", "LessonController@deleteLessonForm");
                    Route::delete("/course-{course}/lesson-{lesson}/delete-lesson-form", "LessonController@deleteLessonForm");
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

    Route::get("/statisticForChartDemo", "App\Author\CourseController@statisticForChartDemo");
});

Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify');


