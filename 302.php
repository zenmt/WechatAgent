<?php
session_start();

include __DIR__ . '/vendor/autoload.php';
use EasyWeChat\Foundation\Application;

include 'config.php';
include 'handler.php';

session_start();
$redirect = $_GET['redirect'];
$target_url = "/302.php?redirect=$redirect";

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

    'oauth' => [
        'scopes'   => ['snsapi_base'],
        'callback' => '/oauth_callback.php?target_url=' . urlencode($target_url),
    ],
];

$app = new Application($config);
$oauth = $app->oauth;
// 未登录
if (empty($_SESSION['wechat_user'])) {
    $_SESSION['target_url'] = $target_url;

    return $oauth->redirect()->send();
    #exit;
}

// 已经登录过
$user = $_SESSION['wechat_user'];

file_put_contents(ERROR_LOG_FILE, "--user---\n". print_r($user, true), FILE_APPEND);

$openId = $user['id'];
$originalId =APP_ID;
$redirectUrl = 'http://test.zenmt.com/' . $redirect;

$content = [
    'uri' => urlencode($redirect),
];


eventHandler($openId, $originalId, ARTICLE_OPENED, json_encode($content));

header("Location: $redirectUrl", true, 301);
exit();

?>
