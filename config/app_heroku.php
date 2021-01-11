<?php
/*
 * Local configuration file to provide any overrides to your app.php configuration.
 * Copy and save this file as app_local.php and make changes as required.
 * Note: It is not recommended to commit files with credentials such as app_local.php
 * into source code version control.
 */

use Cake\Cache\Engine\RedisEngine;

return [
    /*
     * Debug Level:
     *
     * Production Mode:
     * false: No error messages, errors, or warnings shown.
     *
     * Development Mode:
     * true: Errors and warnings shown.
     */
    'debug' => false,

    'App' => [
        'fullBaseUrl' => 'https://kakeibouz.herokuapp.com',
    ],

    /*
     * Security and encryption configuration
     *
     * - salt - A random string used in security hashing methods.
     *   The salt value is also used as the encryption key.
     *   You should treat it as extremely sensitive data.
     */
    'Security' => [
        'salt' => env('SECURITY_SALT', 'ba0239d797f9c9dcdfddc459820cc43444bd4b40f1b12792a81bd5b5722084ef'),
    ],

    /*
     * Connection information used by the ORM to connect
     * to your application's datastores.
     *
     * See app.php for more configuration options.
     */
    'Datasources' => [
        'default' => [
            /**
             * You can use a DSN string to set the entire configuration
             */
            'url' => env('CLEARDB_DATABASE_URL', null),
        ],
    ],

    /*
     * Email configuration.
     *
     * Host and credential configuration in case you are using SmtpTransport
     *
     * See app.php for more configuration options.
     */
    'EmailTransport' => [
        'default' => [
            'host' => 'localhost',
            'port' => 25,
            'username' => null,
            'password' => null,
            'client' => null,
            'url' => env('EMAIL_TRANSPORT_DEFAULT_URL', null),
        ],
    ],

    //Warning: Warning (512): The `redis` extension must be enabled to use RedisEngine. in [/app/vendor/cakephp/cakephp/src/Cache/Cache.php, line 161]
    'Cache' => [
        'default' => [
            'className' => RedisEngine::class,
            'path' => CACHE,
            'url' => env('REDIS_URL', null),
        ],
    ],

    'Session' => [
        'defaults' => 'cache',
    ],
];
