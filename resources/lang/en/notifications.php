<?php

return [
    'title' => 'Уведомления',
    'warning' => 'Внимание',
    'all_notifications' => 'Все уведомления',
    'quota_request_description' => 'Администрация Платформы предлагает сделать курс <a href="/:lang/my-courses/course/:course_id">:course_name</a> доступным по квоте. С условиями предоставления доступа к
курсам по квоте Вы можете ознакомиться в <a href="#rulesQuotaModal:course_id-:notification_id" data-fancybox>правилах</a>. В случае
согласия с Вами свяжутся по указанному контактному телефону для
заключения договора.',
    'quota_request_description_mail' => 'Администрация Платформы предлагает сделать курс <a href=":url">":course_name"</a> доступным по квоте. С условиями предоставления доступа к
курсам по квоте Вы можете ознакомиться в <a href="#">правилах</a>. В случае
согласия с Вами свяжутся по указанному контактному телефону для
заключения договора.',
    'quota_rules_title' => 'Правила публикации курса по квоте',
    'quota_rules_description' => 'Уважаемый «<b>:author_name</b>»
Платформа краткосрочных онлайн курсов Enbek Skills предлагает Вам рассмотреть возможность предоставления Вашего курса «<b>:course_name</b>» 
по специальной квоте для определенных групп населения, утвержденных Министерством труда и социальной защиты населения.
Базовая стоимость Вашего курса по специальной квоте - <b>:course_quota_cost</b> тг.
<br><br>
Стоимость курса по квоте рассчитывается автоматически в зависимости от формата и длительности Вашего курса учётом соответствующих коэффициентов качетсва учебного контента при помощи утвержденного <a href="/:lang/for-authors#calculator" target="_blank">калькулятора</a> стоимости. 
Предоставление Вашего курса по специальной квоте количество охвата потенциальных обучающихся по Вашим курсам.',

    'confirm_btn_title' => 'Согласен',
    'reject_btn_title' => 'Не согласен',
    'confirm_quota_description' => 'Автор курса :course_name согласен сделать его доступным по квоте.',
    'reject_quota_description' => 'Автор курса :course_name отказывается сделать его доступным по квоте.',
    'course_quota_wait_contract' => 'Для подверждения доступности по квоте курса :course_name, с Вами свяжется администрация',
    'course_quota_access' => 'Курс :course_name теперь доступен по квоте',
    'course_quota_access_denied' => 'Вы отказались от квоты на курс :course_name',

    'publish_course_title' => 'Публикация курса',
    'new_verification_course' => 'Новая заявка на публикацию курса на сайте',
    'new_verification_course_desc' => 'Для подтверждения/отклонения публикации курса перейдите по ссылке',
    'course_verification_title' => 'Подтверждение курса',
    'course_publish' => 'Курс <a href="/:lang/my-courses/course/:course_id">:course_name</a> успешно опубликован',
    'publish_success' => 'успешно опубликован',
    'publish_reject' => 'отклонен модератором и не был опубликован по следующей причине',
    'course_reject' => 'Курс <a href="/:lang/my-courses/course/:course_id">:course_name</a> не был опубликован, причина отказа: <i>":reject_message"</i>',

    'course_reject_description' => 'Ваш курс был отклонен',
    'course_reject_reason' => 'Причина отклонения',

    'password_refresh_mail_message' => 'Вы успешно сбросили пароль',
    'new_password_title' => 'Ваш новый пароль',

    'course_buy_status_on_process' => 'Оплата курса <a href="/:lang/course-catalog/course/:course_id">:course_name</a> в обработке',
    'course_buy_status_success' => 'Вы успешно приобрели курс <a href="/:lang/course-catalog/course/:course_id">:course_name</a>',
    'course_buy_status_failed' => 'Произошла ошибка при оплате курса <a href="/:lang/course-catalog/course/:course_id">:course_name</a>',
    'course_student_finished' => 'Поздравляем с завершением курса <a href="/:lang/course-catalog/course/:course_id">:course_name</a>. Сертификаты доступны в разделе <a href="/:lang/student/my-certificates">Мои сертификаты</a>',
    'new_message' => 'У вас новое <a href="/:lang/dialog/opponent-:opponent_id">сообщение </a> от <i>:user_name</i>',

    're_calculate_quota_cost_message' => 'Стоимость курса :course_name по квоте была обновлена. Текущая стоимость курса по квоте: :course_quota_cost тг.'
];
