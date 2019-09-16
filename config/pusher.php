<?php

/*
 *  * This file is part of Laravel Pusher.
 *   *
 *    * (c) Vincent Klaiber <hello@vinkla.com>
 *     *
 *      * For the full copyright and license information, please view the LICENSE
 *       * file that was distributed with this source code.
 *        */

return [

    /*
     *     |--------------------------------------------------------------------------
     *         | Default Connection Name
     *             |--------------------------------------------------------------------------
     *                 |
     *                     | Here you may specify which of the connections below you wish to use as
     *                         | your default connection for all work. Of course, you may use many
     *                             | connections at once using the manager class.
     *                                 |
     *                                     */

    'default' => env('PUSHER_CONF', 'local'),

    /*
     *     |--------------------------------------------------------------------------
     *         | Pusher Connections
     *             |--------------------------------------------------------------------------
     *                 |
     *                     | Here are each of the connections setup for your application. Example
     *                         | configuration has been included, but you may add as many connections as
     *                             | you would like.
     *                                 |
     *                                     */

    'connections' => [

        'production' => [
            'auth_key' => '445cfb393c6efef1554f',
            'secret' => 'a3658dc65295ad9ad175',
            'app_id' => '220256',
            'options' => [],
            'host' => null,
            'port' => null,
            'timeout' => null,
        ],

        'staging' => [
            'auth_key' => '9ab1e193e75513e3d511',
            'secret' => '2fcc755fa18fbf9169bd',
            'app_id' => '220255',
            'options' => [],
            'host' => null,
            'port' => null,
            'timeout' => null,
        ],

        'local' => [
            'auth_key' => '92ee794c4e4561cd71f7',
            'secret' => 'b3e5cf90cd7d8ea4268d',
            'app_id' => '209207',
            'options' => [],
            'host' => null,
            'port' => null,
            'timeout' => null,
        ],

    ],

];

