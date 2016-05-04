<?php

ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

require '../src/Angular.php';

$start_time = microtime(true);

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

// 实例化
$view = new Angular($config);

// 导航
$navs = [
    ['title' => '首页', 'url' => '#/'],
    ['title' => '博客', 'url' => '#/blog'],
    ['title' => '图片', 'url' => '#/pic'],
    ['title' => '留言', 'url' => '#/msg'],
];

// 模拟用户列表
$data = [
    'title' => 'Hello PHP Angular',
    'list'  => [
        ['id' => 1, 'name' => 'user_1', 'email' => 'email_1@qq.com', 'status' => 1],
        ['id' => 2, 'name' => 'user_2', 'email' => 'email_2@qq.com', 'status' => 0],
        ['id' => 3, 'name' => 'user_3', 'email' => 'email_3@qq.com', 'status' => -1],
        ['id' => 4, 'name' => 'user_4', 'email' => 'email_4@qq.com', 'status' => 1],
        ['id' => 5, 'name' => 'user_5', 'email' => 'email_5@qq.com', 'status' => 1],
    ],
];

// 树状结构
$menus = [
    [
        'title' => '菜单1',
        'sub'   => [
            ['title' => '菜单1.1'],
            ['title' => '菜单1.2'],
            ['title' => '菜单1.3'],
            ['title' => '菜单1.4'],
        ]
    ],
    [
        'title' => '菜单2',
        'sub'   => [
            ['title' => '菜单2.1'],
            ['title' => '菜单2.2'],
            ['title' => '菜单2.3'],
            ['title' => '菜单2.4'],
        ]
    ],
    [
        'title' => '菜单3',
        'sub'   => [
            [
                'title' => '菜单3.1',
                'sub'   => [
                    ['title' => '菜单3.1.1'],
                    ['title' => '菜单3.1.2'],
                    [
                        'title' => '菜单3.1.3',
                        'sub'   => [
                            ['title' => '菜单3.1.3.1'],
                            ['title' => '菜单3.1.3.2'],
                        ]
                    ],
                ]
            ],
            ['title' => '菜单3.2'],
            ['title' => '菜单3.3'],
            ['title' => '菜单3.4'],
        ]
    ],
];

$view->assign('pagecount', 100);
$view->assign('p', isset($_GET['p']) ? $_GET['p'] : 1);
$view->assign('page', function ($p) {
    return '/?p=' . $p;
});

// 向模板引擎设置数据
$view->assign($data);
$view->assign('start_time', $start_time);
$view->assign('menus', $menus);
$view->assign('navs', $navs);


// 输出解析结果
$view->display('index');

// 返回输出结果
// $html = $view->fetch('index');
// echo $html;

// 获取混编代码
// $php_code = $view->compiler('index');
