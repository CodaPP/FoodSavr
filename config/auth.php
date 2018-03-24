<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | Supported: "session", "token"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'token',
            'provider' => 'users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        // Simple example (suitable for most cases)
//        'simple' => [
//            'driver' => 'simple',
//            'model' => App\User::class
//        ],
//
//        // Advanced example
//        'advanced' => [
//            'driver' => 'simple',
//            'model' => App\User::class,

//            // Query modifiers
//            'only' => [
//                // only users with email = example@email.com
//                'email' => 'example@email.com',
//                // only users with ID 1, 2 or 3
//                'id' => [1, 2, 3]
//            ],
//
//            // Any model scope
//            'scope' => 'scopeName',
//            // ...or
//            'scope' => [
//                'scopeName',
//                'scopeWithArguments' => ['arg1', 'arg2']
//            ],

            // Cache prefix can be configured if you want to use multiple independent providers.
            // This will allow clients to have multiple tokens (one per each unique prefix).
            // On the other hand, you can restrict users to have a sinlgle token by providing same prefix.
            // Default: no prefix
            // IMPORTANT: this prefix will will be appended to the `simple_tokens.cache_prefix` config entry.
//            'cache_prefix' => '',
//
//            // Token expiration time in minutes.
//            // You can overwrite default value from the `simple_tokens.token_ttl` config entry here.
//            'token_ttl' => 60
//        ]
        'users' => [
            'driver' => 'eloquent',
            'model' => App\User::class,
        ],

//         'users' => [
//             'driver' => 'database',
//             'table' => 'users',
//         ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | You may specify multiple password reset configurations if you have more
    | than one user table or model in the application and you want to have
    | separate password reset settings based on the specific user types.
    |
    | The expire time is the number of minutes that the reset token should be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
        ],
    ],

];
