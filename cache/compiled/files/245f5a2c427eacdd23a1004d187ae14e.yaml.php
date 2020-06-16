<?php
return [
    '@class' => 'Grav\\Common\\File\\CompiledYamlFile',
    'filename' => '/home/aozfgkeb/public_html/drivingschoolnorbury.com/user/config/plugins/email.yaml',
    'modified' => 1522421408,
    'data' => [
        'enabled' => true,
        'from' => 'joe@test.com',
        'from_name' => 'Joe',
        'to' => 'joe@test.com',
        'to_name' => 'Joe',
        'mailer' => [
            'engine' => 'mail',
            'smtp' => [
                'server' => 'localhost',
                'port' => 25,
                'encryption' => 'none'
            ],
            'sendmail' => [
                'bin' => '/usr/sbin/sendmail'
            ]
        ],
        'content_type' => 'text/html',
        'debug' => false
    ]
];
