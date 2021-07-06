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
    Route::post('/ajax_upload_image', 'AjaxUploadController@ajax_upload_image')->name('ajax_upload_image');
    Route::post('/ajax_upload_file', 'AjaxUploadController@ajax_upload_file')->name('ajax_upload_file');

    Route::post('/ajaxUploadImage', 'AjaxUploadController@ajaxUploadPic');
    Route::post('/ajaxUploadImageContent', 'AjaxUploadController@ajaxUploadPicContent');
    Route::post('/ajaxUploadFile', 'AjaxUploadController@ajaxUploadFile')->name('ajaxUploadFile');

    // Тест для Айтана
    Route::post('/ajaxUploadImageTest', 'AjaxUploadController@ajaxUploadPicTest');
    Route::post('/ajaxUploadFilesTest', 'AjaxUploadController@ajaxUploadFilesTest');

});

Route::get("/{lang}/auth_sso", "Auth\EnbekPassportController@login")->name('auth_sso');

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
            // Маршруты
            Route::group(['middleware' => 'check.permission:admin.routes'], static function () {
                Route::get('/routes/contract-free', 'RoutesController@contractFree')->name('admin.routes.contract_free');
                Route::get('/routes/contract-quota', 'RoutesController@contractQuota')->name('admin.routes.contract_quota');
                Route::get('/routes/contract-paid', 'RoutesController@contractPaid')->name('admin.routes.contract_paid');
                Route::get('/routes/avr', 'RoutesController@avr')->name('admin.routes.avr');

                Route::get('/routes/{type}/create', 'RoutesController@create')->name('admin.routes.create');
                Route::post('/routes/{type}/store', 'RoutesController@store')->name('admin.routes.store');
                Route::get('/routes/{type}/{route_id}/edit', 'RoutesController@edit')->name('admin.routes.role.edit');
                Route::post('/routes/{type}/{route_id}/update', 'RoutesController@update')->name('admin.routes.role.update');
                Route::delete('/routes/{route_id}/destroy', 'RoutesController@destroy')->name('admin.routes.destroy');
            });
            // Курсы
            Route::group(['middleware' => 'check.permission:admin.courses'], static function () {
                Route::get('/courses/index', 'CourseController@index');

                Route::get('/courses/wait-check-contracts', 'CourseController@waitCheckContracts')->name('admin.courses.wait_check_contracts');
                Route::get('/courses/wait-signing-author', 'CourseController@waitSigningAuthor')->name('admin.courses.wait_signing_author');
                Route::get('/courses/wait-signing-admin', 'CourseController@waitSigningAdmin')->name('admin.courses.wait_signing_admin');

                Route::get('/courses/wait_verification', 'CourseController@wait_verification');
                Route::get('/courses/unpublished', 'CourseController@unpublished_index');
                Route::get("/courses/drafts", "CourseController@drafts_index");
                Route::get("/courses/deleted", "CourseController@deleted_index");
                Route::get('/courses/published', 'CourseController@published_index');
                Route::get('/course/{item}', 'CourseController@view');
                Route::post('/course/publish/{item}', 'CourseController@publish')->name('admin.courses.publish');
                Route::post('/course/reject/{item}', 'CourseController@reject')->name('admin.courses.reject');
                Route::post('/course/unpublish/{item}', 'CourseController@unpublish');
                Route::post('/course/accept/{item}', 'CourseController@accept')->name('admin.courses.accept');
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
            // Маршрутизация договоров в курсах
            Route::group(['middleware' => 'check.permission:admin.courses'], static function () {
                Route::post('/contracts/{course_id}/{type}/start', "CourseRoutingController@start")->name('admin.contracts.routing.start');
            });
            // Договоры
            Route::group(['middleware' => 'check.permission:admin.contracts'], static function () {
                Route::get('/contracts/all', 'ContractsController@all')->name('admin.contracts.all');
                Route::get('/contracts/signed', 'ContractsController@signed')->name('admin.contracts.signed');
                Route::get('/contracts/distributed', 'ContractsController@distributed')->name('admin.contracts.distributed');
                Route::get('/contracts/rejected-by-author', 'ContractsController@rejectedByAuthor')->name('admin.contracts.rejected_by_author');
                Route::get('/contracts/rejected-by-admin', 'ContractsController@rejectedByAdmin')->name('admin.contracts.rejected_by_admin');
                Route::get('/contracts/pending', 'ContractsController@pending')->name('admin.contracts.pending');

                Route::get('/contracts/{contract_id}/view', 'ContractsController@view')->name('admin.contracts.view');
                Route::get('/contracts/{contract_id}/get-contract-html', 'ContractsController@getContractHtml')->name('admin.contracts.get_contract_html');
                Route::get('/contracts/{course_id}/{type}/get-contract-html-preview', 'ContractsController@getContractHtmlPreview')->name('admin.contracts.get_contract_html_preview');
                Route::get('/contracts/{course_id}/{type}/generate-preview-contract', 'ContractsController@previewContract')->name('admin.contracts.generate_preview_contract');

                Route::post('/contracts/{contract_id}/reject-contract', 'ContractsController@rejectContract')->name('admin.contracts.reject_contract');

                Route::get("/contracts/signing/{contract_id}/xml", "ContractsController@xml")->name('admin.contracts.contract.xml'); // Формирование XML для подписания
                Route::post("/contracts/signing/{contract_id}/next", "ContractsController@next")->name('admin.contracts.contract.next'); // Отправка подписанного договора
                Route::get("/contracts/signing/{contract_id}/next", "ContractsController@next")->name('admin.contracts.contract.next'); // Отправка обычного договора

                Route::post("/contracts/rejecting/{contract_id}/admin", "ContractsController@rejectContractByAdmin")->name('admin.contracts.contract.reject_by_admin');
                Route::post("/contracts/rejecting/{contract_id}/moderator", "ContractsController@rejectContractByModerator")->name('admin.contracts.contract.reject_by_moderator');
                Route::get("/contracts/rejecting/{contract_id}/cancel", "ContractsController@rejectContractByAdminCancel")->name('admin.contracts.contract.reject_by_admin_cancel');
            });

            // АВР
            Route::group(['middleware' => 'check.permission:admin.avr'], static function () {
                Route::get("/avr/all", "AVRController@all")->name('admin.avr.all');
                Route::get('/avr/pending', 'AVRController@pending')->name('admin.avr.pending');
                Route::get('/avr/signed', 'AVRController@signed')->name('admin.avr.signed');
                Route::get("/avr/generate", "AVRController@generate")->name('admin.avr.generate');

                Route::get('/avr/{avr_id}/view', 'AVRController@view')->name('admin.avr.view');
                Route::get('/avr/{avr_id}/get-avr-html', 'AVRController@getAVRHtml')->name('admin.avr.get_contract_html');
//                Route::get('/contracts/{course_id}/{type}/get-contract-html-preview', 'ContractsController@getContractHtmlPreview')->name('admin.contracts.get_contract_html_preview');
//                Route::get('/contracts/{course_id}/{type}/generate-preview-contract', 'ContractsController@previewContract')->name('admin.contracts.generate_preview_contract');


                Route::get("/avr/signing/{avr_id}/next", "AVRController@next")->name('admin.avr.next'); // Заглушка пока нет эцп
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
            // Проверка документа
            Route::get("/verify", "DocumentController@verify")->name('public.document.verify');
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
            Route::get("/getAuthorsByCompanyName", "CourseController@getAuthorsByCompanyName");
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
            Route::post("/agree/{user_id}", "UserController@agree");
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
                    Route::get("/profile-requisites", "UserController@profile_requisites");
                    Route::post("/update_profile-requisites", "UserController@update_profile_requisites")->name('update_profile_requisites');
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
                    Route::get("/my-courses", "CourseController@myCourses")->name('author.courses.my_courses');
                    Route::get("/my-contracts", "ContractsController@index")->name('author.contracts.index');
                    Route::get("/my-avr", "AVRController@index")->name('author.avr.index');
                    Route::get("/contract/{contract_id}/download", "ContractsController@download")->name('author.contracts.download');
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
                    Route::get("/my-courses/drafts", "CourseController@myCourses")->name('author.courses.drafts');
                    // Неопубликованные курсы
                    Route::get("/my-courses/unpublished", "CourseController@myCourses")->name('author.courses.unpublished');
                    // На проверке
                    Route::get("/my-courses/on-check", "CourseController@myCourses")->name('author.courses.on_check');
                    // Удаленные курсы
                    Route::get("/my-courses/deleted", "CourseController@myCourses")->name('author.courses.deleted');
                    // Редактирование курса
                    Route::get("/my-courses/course/{item}", "CourseController@courseShow");
                    // На подписании
                    Route::get("/my-courses/signing", "CourseController@myCourses")->name('author.courses.signing');
                    Route::get("/my-courses/signing/{contract_id}/contract", "CourseController@contract")->name('author.courses.signing.contract');
                    Route::get("/my-courses/signing/{contract_id}/contract/doc", "CourseController@contractDoc")->name('author.courses.signing.contractDoc');
                    Route::get("/my-courses/signing/{contract_id}/contract-reject", "CourseController@contractReject")->name('author.courses.signing.contract.reject');

                    Route::get("/my-courses/signing/{contract_id}/xml", "CourseController@xml")->name('author.courses.signing.contract.xml'); // Формирование XML для подписания
                    Route::post("/my-courses/signing/{contract_id}/next", "CourseController@next")->name('author.courses.signing.contract.next'); // Отправка подписанного договора
                    Route::get("/my-courses/signing/{contract_id}/next", "CourseController@next")->name('author.courses.signing.contract.next'); // Отправка обычного договора

                    // avr
                    Route::get("/my-avr/{avr_id}/avr", "AvrController@avr")->name('author.avr.view');
                    Route::get("/my-avr/{avr_id}/avrDoc", "AvrController@avrDoc")->name('author.avr.signing.avrDoc');
                    Route::get("/my-avr/signing/{avr_id}/next", "AvrController@next")->name('author.avr.signing.next'); // Заглушка пока нет эцп
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


