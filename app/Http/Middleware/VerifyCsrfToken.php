<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/callbackPaymentOrder',
        '/ajaxUploadImageTest',
        '/ajaxUploadFilesTest',
        '/ru/getProfessionsByName',
        '/ru/getSkillsByData',
        '/ru/getAuthorsByName',
        '/ru/getSkills',
        '/kk/getProfessionsByName',
        '/kk/getSkillsByData',
        '/kk/getAuthorsByName',
        '/kk/getSkills',
        '/en/getProfessionsByName',
        '/en/getSkillsByData',
        '/en/getAuthorsByName',
        '/en/getSkills',


        '/ru/getCourseData/*',
        '/move-theme',
        '/move-item',
        '/delete-theme',
        '/edit-theme',
        '/create-theme',

        '/move-lesson',
        '/delete-lesson'
    ];
}
