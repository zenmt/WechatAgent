<?php
file_put_contents("./logs/oauth_callback.log", 'begin');

include __DIR__ . '/vendor/autoload.php';
include 'config.php';

use EasyWeChat\Foundation\Application;

$config = [
    'debug'  => true,

    'log'    => [
        'level' => 'debug',
        'file'  => ERROR_LOG_FILE,
    ],

    'app_id'  => APP_ID,
    'secret'  => APPSECRET,
    'token'   => TOKEN,
    'aes_key' => AESKEY,

    #'oauth' => [
    #    'scopes'   => ['snsapi_base'],
    #    #'scopes'   => ['snsapi_userinfo'],
    #    'callback' => '/wx/oauth_callback',
    #],
];

$app = new Application($config);
$oauth = $app->oauth;


$targetUrl = '/';
if (!empty($_SESSION['target_url']))
{
    $targetUrl = $_SESSION['target_url'];
}
else if (!empty($_GET['target_url']))
{
    $targetUrl = $_GET['target_url'];
}

//$targetUrl = empty($_SESSION['target_url']) ? '/' : $_SESSION['target_url'];

// 获取 OAuth 授权结果用户信息
$user = $oauth->user();
$_SESSION['wechat_user'] = $user->toArray();

header('location:'. $targetUrl);
?>
