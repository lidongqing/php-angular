<?php
header('Content-Type: text/html; charset=utf-8;');
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

// 开始时间
$start_time = microtime(true);

require '../src/Angular.php';

// 配置
$config = [
    'debug'            => true, // 是否开启调试, 开启调试会实时生成缓存
    'tpl_path'         => './view/', // 模板根目录
    'tpl_suffix'       => '.html', // 模板后缀
    'tpl_cache_path'   => './cache/', // 模板缓存目录
    'tpl_cache_suffix' => '.php', // 模板后缀
    'attr'             => 'php-', // 标签前缀
    'max_tag'          => 10000, // 标签的最大解析次数
];

// 自定义扩展
Angular::extend('diy', function ($content, $param) {
    $return = '<pre>';

    $return .= '参数:';
    unset($param[0], $param[1], $param[2], $param[3], $param[4], $param[5]);
    $return .= htmlspecialchars(print_r($param, true));

    $return .= "\n" . '解析前:' . "\n";
    $return .= htmlspecialchars($param['html']);

    $return .= "\n" . '解析后:' . "\n";
    $new         = str_replace('hello', 'hello world', $param['html']);
    $sub_content = str_replace($param['exp'], '', $new);
    $return .= htmlspecialchars($sub_content);

    $return .= '</pre>';
    return str_replace($param['html'], $return, $content);

});

function load($key)
{
    return include './data/' . $key . '.php';
}

// 实例化
$view = new Angular($config);

// 导航
$navs = load('navs');
$view->assign('navs', $navs);
$view->assign('start_time', $start_time);
