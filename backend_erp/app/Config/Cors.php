<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Cors extends BaseConfig
{
    public array $default = [
        'allowedOrigins' => [
            '*',
        ],
        'allowedOriginsPatterns' => [],
        'supportsCredentials'    => false,
        'allowedHeaders'         => [
            'X-Requested-With',
            'Content-Type',
            'Accept',
            'Origin',
            'Authorization'
        ],
        'exposedHeaders'         => [],
        'allowedMethods'         => [
            'GET',
            'POST',
            'OPTIONS',
            'PUT',
            'DELETE'
        ],
        'maxAge'                 => 7200,
    ];
}
