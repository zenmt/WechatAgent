<?php
include __DIR__ . '/vendor/autoload.php';
use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\News;

include 'config.php';

function http_get($url)
{
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/mautic');
    curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/mautic');
    curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
    $file_contents = curl_exec($ch);
    $last_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    curl_close($ch);
    return $last_url;
}

function http_post($url, $params)
{
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/mautic');
    curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/mautic');
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    $file_contents = curl_exec($ch);
    $last_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    curl_close($ch);
}



function eventHandler($openId, $originalId, $eventType, $content)
{

    $post_data = [
        "openId" => $openId,
        "originalId" => $originalId,
        "eventType" => $eventType,
        "content" => $content,
    ];

    file_put_contents(ERROR_LOG_FILE, "[eventHandler] [post_data] ". print_r($post_data, true), FILE_APPEND);

    http_post(ZENMT_EVENT_URL, $post_data);
}


?>
