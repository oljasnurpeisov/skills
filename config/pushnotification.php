<?php

return [
    'gcm' => [
        'priority' => 'normal',
        'dry_run' => false,
        'apiKey' => 'My_ApiKey',
    ],
    'fcm' => [
        'priority' => 'normal',
        'dry_run' => false,
        'apiKey' => 'AAAAsUJ5HDE:APA91bGhAD6rSrMH8yO79muQ3-IHnrPp2vHLya7pCppANXVhat9EGtyyd4pWqNkjC0zn4zv4KkRTlgt6IgU_xvwPqs1KKsjNPpNgBE0Wd1yDfwO2bwPhS6OOswROPHaiao73o2zmzxMU',
    ],
    'apn' => [
        'certificate' => __DIR__ . '/iosCertificates/pushcert.pem',
        'passPhrase' => 'qwerty123',
        'dry_run' => false,
    ],
];
