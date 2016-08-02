<?php
/**
  * wechat php proxy
  */
include __DIR__ . '/vendor/autoload.php';
include 'config.php';
include 'handler.php';

use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\News;

$options = [
    'debug'  => true,

    'log'    => [
        'level' => 'debug',
        'file'  => ERROR_LOG_FILE,
    ],

    'app_id'  => APP_ID,         // AppID
    'secret'  => APPSECRET,      // AppSecret
    'token'   => TOKEN,          // Token
    'aes_key' => AESKEY,         // EncodingAESKey
];

$app = new Application($options);
$server = $app->server;

$server->setMessageHandler(function ($message) {
    switch ($message->MsgType) {
        case 'event':
            switch ($message->Event) {
                case 'subscribe':
                    eventHandler($message->FromUserName, $message->ToUserName, ACCOUNT_FOLLOWED, '');
                    return '';
                    break;
                case 'unsubscribe':
                    return '';
                    break;
                default:
                    return '';
                    break;
            }
            break;
        case 'text':
            eventHandler($message);
            return '';
            break;
        case 'image':
            # 图片消息...
            break;
        case 'voice':
            # 语音消息...
            break;
        case 'video':
            # 视频消息...
            break;
        case 'location':
            # 坐标消息...
            break;
        case 'link':
            # 链接消息...
            break;
        // ... 其它消息
        default:
            # code...
            break;
    }

    return "Can I help you?";
});

$server->serve()->send();

?>
