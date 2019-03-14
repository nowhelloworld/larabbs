<?php

use Illuminate\Http\Request;

$api = app('Dingo\Api\Routing\Router');
$group = [
    'middleware' => 'api.throttle',
    'limit' => config('api.rate_limits.sign.limit'),
    'expires' => config('api.rate_limits.sign.expires'),
];

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api'
], function($api) {

    $api->group($group, function($api) {
        // 短信验证码
        $api->post('verificationCodes', 'VerificationCodesController@store')
            ->name('api.verificationCodes.store');
        // 用户注册
        $api->post('users', 'UserController@store')
            ->name('api.users.store');
    });
});


