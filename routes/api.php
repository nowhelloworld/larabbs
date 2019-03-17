<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api',
    // 'middleware' => ['serializer:array', 'bindings', 'change-locale']
], function($api) {
    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.sign.limit'),
        'expires' => config('api.rate_limits.sign.expires'),
    ], function($api) {
        // 短信验证码
        $api->post('verificationCodes', 'VerificationCodesController@store')
            ->name('api.verificationCodes.store');
        // 用户注册
        $api->post('users', 'UsersController@store')
            ->name('api.users.store');
        // 图片验证码
        $api->post('captchas', 'CaptchasController@store')
            ->name('api.captchas.store');
        // 流程：
            // 先图片验证http://{{host}}/api/captchas，post参数phone，返回captcha_key和captcha_image_content，captcha_image_content就是图片，验证码内容
            // 再验证验证码 http://{{host}}/api/verificationCodes，post参数captcha_key是图片存的，captcha_code是图片验证码
            // 用户注册http://{{host}}/api/users，参数verification_key是cache的key，是由verificationCodes返回的。name名字，phone电话，verification_code是短信的验证码，password是用填的密码
        // 第三方登录
        $api->post('socials/{social_type}/authorizations', 'AuthorizationsController@socialStore')
            ->name('api.socials.authorizations.store');
        // 客户端要么提交授权码（code），要么提交 access_token 和 openid
        // social_type参数是weixin
        // http://{{host}}/api/socials/:social_type.authorizations

    });
});





