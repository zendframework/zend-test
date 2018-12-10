<?php
/**
 * @see       https://github.com/zendframework/zend-test for the canonical source repository
 * @copyright Copyright (c) 2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-test/blob/master/LICENSE.md New BSD License
 */

$cacheDir = sys_get_temp_dir() . '/zf2-module-test';

if (! is_dir($cacheDir)) {
    mkdir($cacheDir);
}

return [
    'modules' => [
        'Zend\Router',
        'Zend\Validator',
        'Zend\Mvc\Console',
        'Zend\Mvc\Plugin\FlashMessenger',
        'Baz',
    ],
    'module_listener_options' => [
        'config_cache_enabled' => true,
        'cache_dir'            => $cacheDir,
        'config_cache_key'     => 'phpunit',
        'config_static_paths'  => [],
        'module_paths'         => [
            'Baz' => __DIR__ . '/Baz/',
        ],
    ],
    'templates' => [
        'layout'       => 'layout/default',
        'layout_error' => 'layout/error',
        'map'          => [
            'layout/error' => 'templates/layout/error.phtml'
        ]
    ],
];
