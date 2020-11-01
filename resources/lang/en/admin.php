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
        'organizations' => [
            'title' => 'Организации',
            'create' => 'Добавить организацию',
            'list' => 'Список организаций',
        ],
        'organization' => [
            'title' => 'Организация',
            'name' => 'Название организации',
            'org' => 'Организация',
            'code' => 'Код подразделения',
            'permissions' => 'Доступы организации',
            'address' => 'Адрес',
            'stat' => [
                'created' => 'Организаций зарегистрировано: <b>:attr</b>',
                'edited' => 'Организаций отредактировано: <b>:attr</b>',
                'removed' => 'Организаций удалено: <b>:attr</b>',
            ],
        ],
        'users' => [
            'title' => 'Пользователи',
            'create' => 'Добавить пользователя',
            'list' => 'Список пользователей',
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
        'courses' => [
            'title' => 'Курсы',
            'list' => 'Список курсов',
            'wait_publish_list' => 'Ожидающие проверки',
            'unpublish_list' => 'Неопубликованные курсы',
            'publish_list' => 'Опубликованные курсы',
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
            '1' => 'На проверке',
            '2' => 'Не опубликован',
            '3' => 'Опубликован',
            '4' => 'Удален',
            'quota_status_0' => 'Нет',
            'quota_status_1' => 'Заявка отправлена автору',
            'quota_status_2' => 'Да',
            'quota_status_3' => 'Заявка отклонена автором',
            'quota_request_title' => 'Запросить доступ к курсу по квотам',
            'unpublish_title' => 'Снять с публикации',
            'course_reject' => 'Курс :course_name не опубликован! Причина отклонения: :rejectMessage',
            'course_published' => 'Курс :course_name успешно опубликован.',
            'course_unpublished' => 'Курс :course_name был успешно снят с публикации.',
            'course_quote_request' => 'Уведомление о публикации курса по квоте было отправлено автору курса',
            'quote_contract_saved' => 'Номер договора успешно сохранен',

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
        'fridges' => [
            'title' => 'Холодильники',
            'create' => 'Добавить холодильник',
            'list' => 'Список холодильников',
        ],
        'dish_producers' => [
            'title' => 'Поставщики',
            'create' => 'Добавить поставщика',
            'list' => 'Список поставщиков',
        ],
        'dish_producer' => [
            'title' => 'Поставщик',
            'name' => 'Название компании',
            'description' => 'Описание'
        ],
        'task_patterns' => [
            'title' => 'Шаблоны заданий',
            'create' => 'Добавить шаблон',
            'list' => 'Список шаблонов',
        ],
        'task_pattern' => [
            'title' => 'Шаблон задания',
            'name' => 'Название',
            'out' => 'Выгрузка',
            'in' => 'Загрузка',
            'count' => 'Количество',
            'add_dish_for_unload' => 'Добавить блюдо для выгрузки',
            'add_dish_for_load' => 'Добавить блюдо для загрузки',
        ],
        'tasks' => [
            'title' => 'Задания',
            'create' => 'Добавить задание',
            'list' => 'Список заданий',
        ],
        'task' => [
            'title' => 'Задание',
            'name' => 'Название',
            'courier' => 'Курьер',
            'pattern' => 'Шаблон',
            'patterns' => [
                'Нет',
                'Да',
            ],
            'date' => 'Дата к исполнению',
            'status' => 'Статус',
            'statuses' => [
                'new' => 'Новый',
                'deliver' => 'Передано курьеру',
                'unloaded' => 'Выгружено',
                'loaded' => 'Загружено',
                'completed' => 'Выполнено',
            ],
            'export' => 'На печать',
            'notifications' => [
                'in_progress' => 'Ошибка: Задание на исполнении',
            ],
        ],
        'video_records' => [
            'title' => 'Заявки',
            'list' => 'Список заявок',
        ],
        'video_record' => [
            'title' => 'Заявка',
            'name' => 'Название',
            'amount' => 'Сумма',
            'status' => 'Статус',
            'source' => 'Источник',
            'video_f' => 'Видео - Ракурс 1',
            'video_s' => 'Видео - Ракурс 2',
            'add_dish' => 'Добавить блюдо',
            'statuses' => [
                'Видео на обработке',
                'Заявка на рассмотрении',
                'Заявка прошла проверку 1',
                'Заявка прошла проверку 2',
                'Заявка обработана',
            ],
            'notifications' => [
                'invalid_recognition_type' => 'Ошибка: Неправильный тип видео',
                'data_already_registered' => 'Ошибка: Блюда уже внесены',
                'data_not_prev_registered' => 'Ошибка: Не пройдена проверка 1 ',
            ],
        ],
        'payments' => [
            'title' => 'Платежи',
            'list' => 'Список платежей',
        ],
        'payment' => [
            'title' => 'Платеж',
            'name' => 'Название',
            'amount' => 'Сумма',
            'status' => 'Статус',
            'source' => 'Источник',
            'statuses' => [
                'Платеж обрабатывается',
                'Оплачено, необходимо подтвердить платеж',
            ],
        ],
        'purchases' => [
            'title' => 'Список покупок',
            'list' => 'Список покупок',
        ],
        'purchase' => [
            'title' => 'Покупка',
            'dishes' => 'Купленная еда',
            'cost' => 'Стоимость',
            'sum' => 'Итого',
            'tg' => 'тг',
            'tenge' => 'тенге',
        ],
        'reviews' => [
            'title' => 'Сообщения',
            'list' => 'Список сообщений',
        ],
        'review' => [
            'title' => 'Сообщение',
            'last_message' => 'Последнее сообщение',
            'from' => 'От',
            'status' => 'Статус',
            'statuses' => [
                'Не отвечено',
                'Отвечено',
            ],
            'description' => 'Текст сообщения',
            'add_answer' => 'Добавить ответ',
        ],
        'errors' => [
            'title' => 'Ошибки',
            'list' => 'Список ошибок',
        ],
        'error' => [
            'title' => 'Ошибка',
            'type' => 'Тип ошибки',
            'target_id' => 'ID объекта ошибки',
            'description' => 'Описание',
            'status' => 'Статус',
            'source' => 'Источник',
            'statuses' => [
                'В обработке',
                'Решена',
            ],
        ],
        'dictionaries' => [
            'title' => 'Справочники',
            'create' => 'Добавить справочник',
            'list' => 'Список справочников',
        ],
        'regions' => [
            'title' => 'Местоположение',
            'create' => 'Добавить местоположение',
            'list' => 'Список местоположений',
        ],
        'region' => [
            'title' => 'Регион',
            'name' => 'Название',
            'stat' => [
                'created' => 'Регионов зарегистрировано: <b>:attr</b>',
                'edited' => 'Регионов отредактировано: <b>:attr</b>',
                'removed' => 'Регионов удалено: <b>:attr</b>',
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
    ],
    'labels' => [
        'copyright' => 'Copyright © 2019<br>Buffet.kz is registered trademark',
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
    ],
];
