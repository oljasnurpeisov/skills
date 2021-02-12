<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Сіз қабылдауыңыз керек: attribute.',
    'active_url' => 'Өріс :attribute жарамсыз URL мекен-жайы бар.',
    'after' => 'Өріс :attribute күні үлкен болу керек :date.',
    'after_or_equal' => 'Өріс :attribute күн үлкен немесе тең болуы керек :date.',
    'alpha' => 'Өріс :attribute тек әріптер болуы мүмкін.',
    'alpha_dash' => 'Өріс :attribute тек әріптер, сандар, сызықша және төменгі астын сызу болуы мүмкін.',
    'alpha_num' => 'Өріс :attribute тек әріптер мен сандарды болуы мүмкін.',
    'array' => 'Өріс :attribute массив болуы керек.',
    'before' => 'Өріс :attribute ертерек күн болуы керек :date.',
    'before_or_equal' => 'Өріс :attribute ертерек күн болуы керек немесе тең болуы керек :date.',
    'between' => [
        'numeric' => 'Өріс :attribute :min және :max аспауға тиіс.',
        'file' => 'Өрістегі файл өлшемі:attribute арасында болу керек :min және :max Килобайт(а).',
        'string' => 'Өрістегі таңбалар саны :attribute арасында болуы керек :min және :max.',
        'array' => 'Өрістегі элементтер саны :attribute арасында болу керек :min және :max.',
    ],
    'boolean' => 'Өріс :attribute логикалық типтің мәні болуы керек.',
    'confirmed' => 'Өріс :attribute растаумен сәйкес келмейді.',
    'date' => 'Өріс :attribute күн емес.',
    'date_equals' => 'Өріс :attribute тең күн болуы керек :date.',
    'date_format' => 'Өріс :attribute форматқа сәйкес келмейді :format.',
    'different' => 'Өрістер :attribute және :other ажыратылуға тиіс.',
    'digits' => 'Сандық өріс ұзындығы :attribute болу керек :digits.',
    'digits_between' => 'Сандық өріс ұзындығы :attribute болу керек :min және :max.',
    'dimensions' => 'Өріс :attribute жарамсыз кескін өлшемдері бар.',
    'distinct' => 'Өріс :attribute қайталанатын мән бар.',
    'email' => 'Өріс :attribute жарамды электрондық пошта мекенжайы болуы керек.',
    'ends_with' => 'Өріс :attribute келесі мәндердің бірімен аяқталуы керек :values',
    'exists' => 'Таңдалған мән :attribute үшін жарамсыз.',
    'file' => 'Өріс :attribute файл болу тиіс.',
    'filled' => 'Өріс :attribute толтыру үшін міндетті.',
    'gt' => [
        'numeric' => 'Өріс :attribute артық болуы керек :value.',
        'file' => 'Өрістегі файл өлшемі :attribute артық болуы керек :value Килобайт(а).',
        'string' => 'Өрістегі символдар саные :attribute артық болуы керек :value.',
        'array' => 'Өрістегі элементтер саны :attribute артық болуы керек :value.',
    ],
    'gte' => [
        'numeric' => 'Өріс :attribute болу керек :value немесе артық.',
        'file' => 'Өрістегу файлдың өлшемі :attribute болу керек :value Килобайт(а) немесе артық.',
        'string' => 'Өрістегі символдар саны :attribute болу керек :value немесе артық.',
        'array' => 'Өрістегі элементтер саны :attribute болу керек :value немесе артық.',
    ],
    'image' => 'Компанияның логотипі кескін болуы керек',
    'in' => 'Таңдалған мән :attribute үшін қате.',
    'in_array' => 'Өріс :attribute онда жоқ :other.',
    'integer' => 'Өріс :attribute бүтін сан болуы керек.',
    'ip' => 'Өріс :attribute жарамды IP-мекен-жай болуы тиіс.',
    'ipv4' => 'Өріс :attribute жарамды IPv4- мекен-жай болуы тиіс.',
    'ipv6' => 'Өріс :attribute жарамды IPv6- мекен-жай болуы тиіс.',
    'json' => 'Өріс :attribute JSON жолы болу керек.',
    'lt' => [
        'numeric' => 'Өріс :attribute кем болу керек :value.',
        'file' => 'Өрістегі файл өлшемі :attribute кем болу керек :value Килобайт(а).',
        'string' => 'Өрістегі символдар саны :attribute кем болу керек :value.',
        'array' => 'Өрістегі элементтер саны :attribute кем болу керек :value.',
    ],
    'lte' => [
        'numeric' => 'Өріс :attribute болу керек :value немесе кем.',
        'file' => 'Өрістегі файл өлшемі :attribute болу керек :value Килобайт(а) немесе кем.',
        'string' => 'Өрістегі символдар саны :attribute болу керек :value немесе кем.',
        'array' => 'Өрістегі элементтер саны :attribute болу керек :value немесе кем.',
    ],
    'max' => [
        'numeric' => 'Өріс :attribute артық болмауы керек :max.',
        'file' => 'Өрістегі файл өлшемі :attribute артық болмауы керек :max Килобайт(а).',
        'string' => 'Өрістегі символдар саны :attribute аспауы тиіс :max.',
        'array' => 'Өрістегі элементтер саны :attribute аспауы тиіс :max.',
    ],
    'mimes' => 'Компанияның логотипі файл типі болу керек :values.',
    'mimetypes' => 'Компанияның логотипі файл типі болу керек :values.',
    'min' => [
        'numeric' => 'Өріс :attribute кем болмауы керек :min.',
        'file' => 'Өрістегі файл өлшемі :attribute кем болмауы керек :min Килобайт(а).',
        'string' => 'Өрістегі символдар саны :attribute кем болмауы керек :min.',
        'array' => 'Өрістегі элементтер саны :attribute кем болмауы керек :min.',
    ],
    'multiple_of' => 'Өрістің мәні :attribute еселік сан болуы керек :value',
    'not_in' => 'Таңдалған мән :attribute үшін қате.',
    'not_regex' => 'Таңдалған формат :attribute үшін қате.',
    'numeric' => 'Өріс :attribute сан болуы керек.',
    'password' => 'пароль дұрыс емес.',
    'present' => 'Өріс :attribute болуы керек.',
    'regex' => 'Өріс :attribute қате форматы бар.',
//    'required' => 'Бұлөріс міндетті түрде толтырылуы тиіс.',
    'required' => 'Өріс :attribute толтыру үшін міндетті',
    'required_if' => 'Өріс :attribute толтыру үшін міндетті, қашан :other тең :value.',
    'required_unless' => 'Өріс :attribute толтыру үшін міндетті, қашан :other тең емес :values.',
    'required_with' => 'Өріс :attribute толтыру үшін міндетті, қашан :values көрсетілген болса.',
    'required_with_all' => 'Өріс :attribute толтыру үшін міндетті, қашан  :values көрсетілген болса.',
    'required_without' => 'Өріс :attribute толтыру үшін міндетті, қашан:values көрсетілмеген болса.',
    'required_without_all' => 'Өріс :attribute толтыру үшін міндетті, қашан көрсетілген шарттардың :values бірі де көрсетілмеген болса.',
    'same' => 'Өрістердің мәндері :attribute және :other сәйкес келуі тиіс.',
    'size' => [
        'numeric' => 'Өріс :attribute тең болуы керек :size.',
        'file' => 'Өрістегі файл өлшемі :attribute тең болу керек :size Килобайт(а).',
        'string' => 'Өрістегі символдар саны :attribute тең болуы керек :size.',
        'array' => 'Өрістегі элементтер саны :attribute тең болу керек :size.',
    ],
    'starts_with' => 'Өріс :attribute келесі мәндердің бірінен басталуы керек :values',
    'string' => 'Өріс :attribute жол болуы керек.',
    'timezone' => 'Өріс :attribute жарамды уақыт белдеуі болуы керек.',
    'unique' => 'Өрістің мұндай мәні :attribute бар.',
    'uploaded' => 'Өрісті жүктеу :attribute сәтсіз аяқталды.',
    'url' => 'Өріс :attribute қате URL форматы бар.',
    'uuid' => 'Өріс :attribute дұрыс UUID болуы керек.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
        'email' => [
            'unique' => 'Енгізілген E-mail үшін есептік жазба тіркелген',
            'exists' => 'Есептік жазба тіркелмеген',
        ],
        'email_forgot_password' => [
            'unique' => 'Енгізілген E-mail үшін есептік жазба тіркелген',
            'exists' => 'Есептік жазба тіркелмеген',
        ],
        'email_register' => [
            'unique' => 'Енгізілген E-mail үшін есептік жазба тіркелген',
            'exists' => 'Есептік жазба тіркелмеген',
        ],

        'password_register' => [
            'min' => '<p> пароль келесі критерийлерге сәйкес болу керек:</p>
<p>- 8 символдан кем емес </p>
<p>- тек латын символдары және сандары </p>
<p>- минимум бір санды қамтиды </p>
<p>- кіші және бас әріптерден тұрады </p>
<p>- кемінде бір арнайы символ бар: ! " # $ % & \' ( ) * + , - . / : ; > = < ? @ [ \ ] ^ _` { | } ~</p>',
            'max' => '<p> пароль келесі критерийлерге сәйкес болу керек:</p>
<p>- 8 символдан кем емес </p>
<p>- тек латын символдары және сандары </p>
<p>- минимум бір санды қамтиды </p>
<p>- кіші және бас әріптерден тұрады </p>
<p>- кемінде бір арнайы символ бар: ! " # $ % & \' ( ) * + , - . / : ; > = < ? @ [ \ ] ^ _` { | } ~</p>',

            'regex' => '<p> пароль келесі критерийлерге сәйкес болу керек:</p>
<p>- 8 символдан кем емес </p>
<p>- тек латын символдары және сандары </p>
<p>- минимум бір санды қамтиды </p>
<p>- кіші және бас әріптерден тұрады </p>
<p>- кемінде бір арнайы символ бар: ! " # $ % & \' ( ) * + , - . / : ; > = < ? @ [ \ ] ^ _` { | } ~</p>',
            'confirmed' => 'Парольдер сәйкес келуі керек'
        ],
        'iin' => [
            'unique' => 'Берілген ЖСН/БСН деректері бар есептік жазба тіркелген',
            'min' => 'ЖСН/БСН 12 символдан тұруы тиіс',
            'max' => 'ЖСН/БСН 12 символдан тұруы тиіс',
        ],

        'resume_iin' => [
            'unique' => 'Берілген ЖСН деректері бар есептік жазба тіркелген',
            'min' => 'ЖСН 12 символдан тұруы тиіс',
            'max' => 'ЖСН 12 символдан тұруы тиіс',
            'numeric' => 'ЖСН сандардан құрылуы тиіс'
        ],

        'image' => 'Компанияның логотипі кескін болуы керек',
        'mimes' => 'Компанияның логотипі келесі файл типі болу керек: :values.',
        'mimetypes' => 'Компанияның логотипі келесі файл типі болу керек :values.',

        'duration' => [
            'gt' => 'Сабақтың ұзақтығы 0-ден көп болуы керек',
        ],

        'name' => [
            'max' => 'Бұл өріс аспауы керек :max символдар'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
