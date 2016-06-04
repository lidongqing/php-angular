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
                <a class="navbar-brand" href="javascript:void(0);">PHP Angular</a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <?php foreach ($navs as $nav) { ?><li  class="<?php echo $nav["title"] == $title ? 'active' : ''; ?>">
                        <a href="<?php echo $nav["url"]; ?>"><?php echo $nav["title"]; ?></a>
                    </li><?php } ?>
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
    <div  class="container">
    <div class="jumbotron">
        <h1>Hello PHP Angular</h1>
        <p>
            此模板引擎针对能够使用angularjs的 php开发者 或 前端开发者 编写, 主要特点是, 不需要额外的标签定义, 全部使用属性定义, 写好的模板文件后, 在开发工具中无需插件即可格式化为很整洁的代码, 因为套完的模板文件还是规范的html.
        </p>
        <p>
            注: 一个标签上可以使用多个模板属性, 属性有前后顺序要求, 所以要注意属性的顺序, 在单标签上使用模板属性时一定要使用<code>/></code>结束, 如: <br>
            <code>&lt;input php-if="$is_download" type="button" value="下载" /><br>
             &lt;img php-if="$article['pic']" src="{&dollar;article.pic}" /></code><br>
            具体可参考后面的解析结果.
        </p>
        <p>
            github项目地址:
            <a target="_blank" href="https://github.com/php-angular/php-angular">https://github.com/php-angular/php-angular</a>
            <br /> thinkphp5驱动地址:
            <a target="_blank" href="https://github.com/php-angular/thinkphp5">https://github.com/php-angular/thinkphp5</a>
        </p>
        其他框架驱动以后会逐个开发, 请关注: https://github.com/php-angular</p>
        <p>
            <a class="btn btn-primary btn-lg" target="_blank" href="https://github.com/php-angular/php-angular">Git版本库地址</a>
            <a class="btn btn-primary btn-lg" target="_blank" href="http://kancloud.cn/shuai/php-angular" />在线文档</a>
        </p>
    </div>
    <div class="row">
        <div class="col-md-8">
            <h4>表格和分页实例</h4>
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
            <?php if ($pagecount > 1) { ?><nav >
    <ul class="pagination">
        <?php if ($p > 1) { ?><li >
            <a href="<?php echo $page(1); ?>">首页</a>
        </li><?php }  if ($p > 1) { ?><li >
            <a href="<?php echo $page($p - 1); ?>">上一页</a>
        </li><?php }  if ($p - 4 > 2) { ?><li >
            <!-- 这里是 往前十页, 如果第一页显示了, 就隐藏这个'...' 按钮 -->
            <a href="<?php echo $page($p - 10 < 1 ? 1 : $p - 10); ?>"><span>...</span></a>
        </li><?php }  for ($i = $p - 4; $i <= $p + 4; $i++) {  if ($i > 0 && $i <= $pagecount) { ?><li   class="<?php echo $p == $i ? 'disabled':""; ?>">
            <?php if ($p != $i) { ?><a  href="<?php echo $page($i); ?>"><?php echo $i; ?></a><?php }  if ($p == $i) { ?><span ><?php echo $i; ?></span><?php } ?>
        </li><?php }  }  if ($p + 4 < $pagecount) { ?><li >
            <!-- 这里是 后十页, 如果最后一页显示了, 就隐藏这个'...' 按钮 -->
            <a href="<?php echo $page($p + 10 > $pagecount ? $pagecount : $p + 10); ?>"><span>...</span></a>
        </li><?php }  if ($p < $pagecount) { ?><li >
            <a href="<?php echo $page($p + 1); ?>">下一页</a>
        </li><?php }  if ($p < $pagecount) { ?><li >
            <a href="<?php echo $page($pagecount); ?>">尾页 <?php echo $pagecount; ?></a>
        </li><?php } ?>
    </ul>
</nav><?php } ?>

            
            <h4>自定义解析规则</h4>
            <pre><?php var_dump($navs);  ?><pre>

            <?php $i = 0;  $i++;  $i++; ?><div   ><?php echo $i; ?></div>
            <?php $i--; ?><div ><?php echo $i; ?></div>
        </div>
        <div class="col-md-4">
            <h4>无限级菜单输出</h4>
            <?php echo_menu($menus);  function echo_menu($menus) { ?><ul  >
                <?php foreach ($menus as $menu) { ?><li >
                    <?php echo $menu["title"];  if (isset($menu['sub'])) {  echo_menu($menu['sub']);  } ?>
                </li><?php } ?>
            </ul><?php } ?>
        </div>
    </div>
</div>
    <div class="well">
        版权所有 zhaishuaigan@qq.com, 运行时间: <?php echo microtime(true) - $start_time; ?> s
    </div>
</body>

</html>
