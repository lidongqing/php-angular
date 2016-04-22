<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $title; ?></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="http://apps.bdimg.com/libs/bootstrap/3.3.4/css/bootstrap.css" />
        <script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
        <script type="text/javascript" src="http://apps.bdimg.com/libs/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    </head>

    <body>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">Brand</a>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <?php foreach ($navs as $nav) { ?><li  class="<?php echo $nav["title"] == '首页' ? 'active' : ''; ?>"><a href="#"><?php echo $nav["title"]; ?></a></li><?php } ?>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="#">退出</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">用户 <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">我的消息</a></li>
                                <li><a href="#">我的关注</a></li>
                                <li><a href="#">我的文章</a></li>
                                <li><a href="#">个人设置</a></li>
                                <li><a href="#">退出登录</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="jumbotron">
                <h1><?php echo $title; ?></h1>
                <p>...</p>
                <p><a class="btn btn-primary btn-lg" href="https://github.com/php-angular/php-angular" role="button">Git版本库地址</a></p>
            </div>

            <table class="table table-bordered">
                <tr>
                    <th>用户名</th>
                    <th>邮箱</th>
                    <th>操作</th>
                </tr>
                <?php foreach ($list as $user) { ?><tr >
                    <td><?php echo $user["name"]; ?></td>
                    <td><?php echo $user["email"]; ?></td>
                    <td>
                        <a href="#" class="btn btn-primary">禁用</a>
                        <a href="#" class="btn btn-danger">删除</a>
                    </td>
                </tr><?php }  if (!($list)) { ?><tr >
                    <td>没有数据</td>
                </tr><?php } ?>
            </table>

            <?php echo_menu($menus);  function echo_menu($menus) { ?><ul  >
                <?php foreach ($menus as $menu) { ?><li >
                    <?php echo $menu["title"];  if (isset($menu['sub'])) {  echo_menu($menu['sub']);  } ?>
                </li><?php } ?>
            </ul><?php } ?>

            <div class="well">
    这是include的底部 , 运行时间: <?php echo microtime(true) - $start_time; ?>
</div>
        </div>

    </body>
</html>
