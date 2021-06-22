<?php

return [
    'site_name' => 'Enbek',
    'pages' => [
        'login' => [
            'title' => 'Авторизация',
            'submit' => 'Вход',
        ],
        'password_reset' => [
            'title' => 'Восстановление пароля',
            'hint' => 'На указанный вами email было отправлено письмо с инструкцией',
            'submit' => 'Восстановить',
        ],
        'home' => [
            'title' => 'Главная',
        ],
        'deleting' => [
            'title' => 'Удаление',
            'hint' => 'При удалении, все данные будут удалены',
            'submit' => 'Удалить',
        ],

        'reject' => [
            'title' => 'Отказать в активации',
            'hint' => 'Укажите причину отказа в активации',
            'submit' => 'Отказать',
        ],

        'roles' => [
            'title' => 'Роли',
            'create' => 'Добавить роль',
            'list' => 'Список ролей',
        ],
        'role' => [
            'title' => 'Роль',
            'name' => 'Название',
            'slug' => 'Alias',
            'description' => 'Описание',
            'permissions' => 'Доступы пользователя',
        ],
        'users' => [
            'title' => 'Пользователи',
            'create' => 'Добавить пользователя',
            'list' => 'Список всех пользователей',
            'admin_list' => 'Список администраторов',
        ],
        'authors' => [
            'title' => 'Авторы',
            'create' => 'Добавить автора',
            'list' => 'Список авторов',
            'list_inactive' => 'Список неактивированных авторов',
            'list_active' => 'Список активированных авторов',
            'activate_button' => 'Одобрить',
            'reject_button' => 'Отклонить',
        ],
        'students' => [
            'title' => 'Обучающиеся',
            'create' => 'Добавить обучающегося',
            'list' => 'Список обучающихся',
        ],
        'courses' => [
            'title' => 'Курсы',
            'list' => 'Список курсов',
            'wait_publish_list' => 'Ожидающие проверки',
            'unpublish_list' => 'Отклоненные курсы',
            'publish_list' => 'Опубликованные курсы',
            'drafts_list' => 'Черновики',
            'deleted_list' => 'Удаленные курсы',
            'course_name' => 'Название курса',
            'author_email' => 'Email автора',
            'preview' => 'Предварительный просмотр',
            'info_about_course' => 'Информация о курсе',
            'publish_button_title' => 'Опубликовать',
            'reject_button_title' => 'Отклонить',
            'reject_button_title_1' => 'Отклонить публикацию',
            'reject_title' => 'Укажите причину отклонения публикации',
            'course_status_title' => 'Статус курса',
            'course_quota_title' => 'Доступен по квоте',
            '0' => 'Черновик',
            '1' => 'На проверке',
            '2' => 'Не опубликован',
            '3' => 'Опубликован',
            '4' => 'Удален',
            '5' => 'Проверка договора',
            'quota_status_0' => 'Нет',
            'quota_status_1' => 'Заявка отправлена автору',
            'quota_status_2' => 'Да',
            'quota_status_3' => 'Заявка отклонена автором',
            'quota_status_4' => 'Ожидается подтверждение номера договора',
            'course_quota_cost' => 'Стоимость курса по квоте',
            'quota_request_title' => 'Запросить доступ к курсу по квотам',
            'unpublish_title' => 'Снять с публикации',
            'course_reject' => 'Курс :course_name не опубликован! Причина отклонения: :rejectMessage',
            'course_published' => 'Курс :course_name успешно опубликован.',
            'course_unpublished' => 'Курс :course_name был успешно снят с публикации.',
            'course_quote_request' => 'Уведомление о публикации курса по квоте было отправлено автору курса',
            'quote_contract_saved' => 'Номер договора успешно сохранен',
            'back_title' => 'Назад'

        ],
        'user' => [
            'title' => 'Пользователь',
            'surname' => 'Фамилия',
            'name' => 'Имя',
            'middle_name' => 'Отчество',
            'email' => 'Email',
            'iin_bin' => 'ИИН/БИН',
            'type_of_ownership' => 'Форма собственности',
            'company_logo' => 'Логотип компании',
            'company_name' => 'Название организации',
            'permissions' => 'Доступы пользователя',
            'phone' => 'Номер телефона',
            'balance' => 'Баланс',
            'role' => 'Роль пользователя',
            'status' => 'Статус',
            'full_name' => 'Фамилия имя отчество',
            'stat' => [
                'created' => 'Пользователей зарегистрировано: <b>:attr</b>',
                'edited' => 'Пользователей отредактировано: <b>:attr</b>',
                'removed' => 'Пользователей удалено: <b>:attr</b>',
            ],
            'statuses' => [
                '0' => 'Не активирован',
                '1' => 'Активирован',
                '2' => 'Отказано в активации',
            ],
        ],
        'changes' => [
            'hint' => 'Было изменено поле <b>":field"</b> c <span class="red">":original"</span> на <span class="green">":saved"</span>',
            'system' => 'Система',
        ],
        'search' => [
            'title' => 'Глобальный поиск',
            'link' => 'Глобальный поиск',
            'advanced' => 'Расширенный поиск',
            'submit' => 'Искать',
            'reset' => 'Сбросить',

            'query' => 'Поисковая фраза',
            'from' => 'c',
            'to' => 'до',
            'count' => 'Всего найдено',
        ],
        'static_pages' => [
            'title' => 'Страницы',
            'edit_pages' => 'Редактирование страниц',
            'faq' => 'FAQ',
            'help' => 'Помощь',
            'calculator' => 'Калькулятор',
            'course_catalog' => 'Каталог курсов',
            'main' => 'Главная',
            'step_by_step' => 'Шаг за шагом',
            'main_banner_title' => 'Баннер',
            'main_banner_image' => 'Изображение баннера',
            'step_title' => 'Заголовок',
            'step_title_placeholder' => 'Введите заголовок шага',
            'step_description' => 'Описание',
            'step_description_placeholder' => 'Введите описание шага',
            'add_btn' => 'Добавить',
            'image_link' => 'Ссылка для изображения',
            'image_link_placeholder' => 'Введите ссылку для изображения',
            'teaser_title' => 'Аннотация',
            'teaser_placeholder' => 'Введите аннотацию',
            'calculator_teaser' => 'Краткое описание',

            'for_authors' => 'Автор',
            'btn_title' => 'Заголовок кнопки',
            'advantages' => 'Преимущества',
            'title_placeholder' => 'Введите заголовок',
            'description_placeholder' => 'Введите описание',
            'advantage_icon' => 'Иконка преймущества',
            'upload_image' => 'Загрузить изображение',

            'faq_theme_title' => 'Наименование темы',
            'tab_title' => 'Наименование вкладки',
            'tab_description' => 'Описание вкладки',
            'add_tab_btn' => 'Добавить вкладку',
            'add_theme_btn' => 'Добавить Тему',
            'add_theme_title' => 'Добавление темы',
            'edit_theme_title' => 'Редактирование темы'
        ],

        'reports' => [
            'title' => 'Отчеты',
            'authors_report' => 'Отчет по авторам',
            'courses_report' => 'Отчет по курсам',
            'students_report' => 'Отчет по обучающимся',
            'certificates_report' => 'Отчет по сертификатам',

            'name_title' => 'ФИО автора',
            'specialization' => 'Специализация',
            'rating' => 'Рейтинг',
            'courses_count' => 'Количество курсов',
            'courses_paid_count' => 'Количество платных',
            'courses_free_count' => 'Количество бесплатных',
            'courses_by_quota_count' => 'Количество доступных по квоте',
            'courses_students_count' => 'Количество слушателей',
            'courses_certificates_students_count' => 'Количество сертифицированных',
            'courses_students_confirm_qualification_count' => 'Количество подтвердивших квалификацию',

            'course_name' => 'Наименование курса',
            'author_name' => 'Наименование автора',
            'skills' => 'Навыки',
            'group_profession' => 'Группа професии',
            'professions' => 'Професии',
            'course_rate' => 'Рейтинг курса',
            'course_status' => 'Статус курса',
            'quota_access' => 'Доступен по квоте',
            'paid_or_free' => 'Платный /Бесплатный',
            'course_members' => 'Записано обучающихся',
            'course_members_certificates' => 'Получили сертификат',
            'course_members_qualification' => 'Подтвердили квалификацию',

            'name_student' => 'ФИО обучающегося',
            'unemployed' => 'Безработный',
            'quotas_count' => 'Количество оставшихся квот',
            'student_courses_count' => 'Записан на курсы',
            'student_certificates_count' => 'Получил сертификатов',
            'student_qualifications_count' => 'Подтвердил квалификацию'
        ],

        'dialogs' => [
            'title' => 'Обращения',
            'list' => 'Список обращений',
            'opponent_name' => 'ФИО',
            'last_message' => 'Последнее сообщение',
            'name_search' => 'Поиск по ФИО'
        ],

        'help' => [
            'title' => 'Помощь',
            'video' => 'Видеоинструкция',
        ],
    ],
    'labels' => [
        'copyright' => '',
        'welcome' => 'Добро пожаловать',
        'email' => 'Email',
        'password' => 'Пароль',
        'search' => 'Найти',
        'search_result' => 'Результат поиска',
        'search_result_null' => 'По вашему поиску ничего найдено',
        'show_all' => 'Показать <strong><a href=\':url\'>все записи</a></strong>',
        'record_list' => 'Список записей',
        'created_at' => 'Дата создания',
        'updated_at' => 'Дата изменения',
        'deleted_at' => 'Дата удаления',
        'no_records' => 'Нет записей в текущем модуле',
        'fill_field' => 'Заполните поле: :field',
        'permissions' => 'Доступы',
        'update_password' => 'Обновить пароль',
        'save' => 'Сохранить',
        'logout' => 'Выйти',
        'actions' => 'Действия',
        'view' => 'Просмотреть',
        'edit' => 'Редактировать',
        'upload_file' => 'Загрузить документ',
        'upload_image' => 'Загрузить изображение',
        'cancel' => 'Отмена',
        'not_selected' => 'Не выбрано',
        'sync_files' => 'Синхронизировать файлы',
        'upload_files' => 'Загрузить документы',
    ],
    'notifications' => [
        'login' => [
            'invalid_credentials' => 'Пользователь с указанным логином и паролем не найден',
        ],
        'new_password' => 'Пароль пользователя: :password',
        'record_stored' => 'Запись успешно добавлена',
        'record_updated' => 'Запись успешно изменена',
        'record_deleted' => 'Запись успешно удалена',
        'permission_denied' => 'У вас недостаточно прав для доступа к выбранному разделу',
        'page_not_found' => 'Страница не найдена',
        'server_error' => 'Ошибка сервера, попробуйте позднее',
        'greeting' => 'Здравствуйте, :name!',
        'thank' => 'Благодарим за внимание',
        'register' => [
            'title' => 'Регистрация',
            'hint' => 'Вы были зарегистрированы на сайте <b>:site</b>',
            'link' => 'Для захода в личный кабинет, вам необходимо перейти по <b><a href=":link">ссылке</a></b>',
        ],
        'password_reset' => [
            'title' => 'Восстановление пароля',
            'hint' => 'На сайте <b>:site</b> был обновлен пароль от вашего личного кабинета',
            'link' => 'Для захода в личный кабинет, вам необходимо перейти по <b><a href=":link">ссылке</a></b>',
            'invalid' => 'Пользователь с указанным логином не найден',
        ],
        'update_success' => 'Изменения успешно сохранены'
    ],
];
