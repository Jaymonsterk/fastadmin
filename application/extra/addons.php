<?php

return [
    'autoload' => false,
    'hooks' => [
        'upload_config_init' => [
            'qiniu',
        ],
        'upload_delete' => [
            'qiniu',
        ],
        'app_init' => [
            'qiniu',
        ],
    ],
    'route' => [],
    'priority' => [],
];
