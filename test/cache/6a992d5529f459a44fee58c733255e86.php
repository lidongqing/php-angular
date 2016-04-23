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
                    <a class="navbar-brand" href="javascript:void(0);">Brand</a>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <?php foreach ($navs as $nav) { ?><li  class="<?php echo $nav["title"] == '首页' ? 'active' : ''; ?>"><a href="javascript:void(0);"><?php echo $nav["title"]; ?></a></li><?php } ?>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="javascript:void(0);">退出</a></li>
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">用户 <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="javascript:void(0);">我的消息</a></li>
                                <li><a href="javascript:void(0);">我的关注</a></li>
                                <li><a href="javascript:void(0);">我的文章</a></li>
                                <li><a href="javascript:void(0);">个人设置</a></li>
                                <li><a href="javascript:void(0);">退出登录</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="jumbotron">
                <h1><?php echo $title; ?></h1>
                <p>
                    此模板引擎针对能够使用angularjs的php开发者编写, 主要特点是 不需要额外的标签定义, 全部使用属性定义, 写好模板文件在IDE格式化代码的时候很整洁, 因为套完的模板文件还是规范的html
                </p>
                <p>
                    注: 一个标签上可以使用多个模板属性, 属性有前后顺序要求, 所以要注意属性的顺序, 单标签一定要使用<code>/></code>结束, 如 <code>&lt;input type="text" value="{&dollar;article.title}" />, &lt;img src="{&dollar;article.pic}" /></code> 等等, 具体可参考后面章节的解析结果
                </p>
                <p>
                    github项目地址: 
                    <a target="_blank" href="https://github.com/php-angular/php-angular">https://github.com/php-angular/php-angular</a> <br />
                    thinkphp5驱动地址: 
                    <a target="_blank" href="https://github.com/php-angular/thinkphp5">https://github.com/php-angular/thinkphp5</a> 
                </p>
                其他框架驱动以后会逐个开发, 请关注: https://github.com/php-angular</p>
                <p>
                    <a class="btn btn-primary btn-lg" target="_blank" href="https://github.com/php-angular/php-angular">Git版本库地址</a>
                    <a class="btn btn-primary btn-lg" target="_blank" href="http://kancloud.cn/shuai/php-angular" />在线文档</a>
                </p>
            </div>

            <table class="table table-bordered">
                <tr>
                    <th>编号</th>
                    <th>用户名</th>
                    <th>邮箱</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                <?php if ($list) {  foreach ($list as $user) { ?><tr  >
                    <td><?php echo $user["id"]; ?></td>
                    <td><?php echo $user["name"]; ?></td>
                    <td><?php echo $user["email"]; ?></td>
                    <td>
                        <?php switch ($user['status']) {  case 1: ?><span >正常</span><?php break;  case 0: ?><span >已禁用</span><?php break;  case -1: ?><span >已删除</span><?php break;  } ?>
                    </td>
                    <td>
                        <?php if ($user['status'] === 1) { ?><a   href="javascript:void(0);" class="btn btn-xs btn-warning">禁用</a><?php echo ' ';  }  if ($user['status'] === 0) { ?><a   href="javascript:void(0);" class="btn btn-xs btn-primary">启用</a><?php echo ' ';  }  if ($user['status'] >= 0) { ?><a   href="javascript:void(0);" class="btn btn-xs btn-danger">删除</a><?php echo ' ';  }  if ($user['status'] == -1) { ?><a   href="javascript:void(0);" class="btn btn-xs btn-primary">恢复</a><?php echo ' ';  } ?>
                    </td>
                </tr><?php }  }  else { ?><tr >
                    <td colspan="3" class="text-center">没有数据</td>
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
