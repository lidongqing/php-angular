<?php

// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://www.thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 翟帅干 <zhaishuaigan@qq.com> <http://zhaishuaigan.cn>
// +----------------------------------------------------------------------
/**
 * Angular模板引擎
 */
class Angular {

    private $config    = array(
        'tpl_path'   => './view/',
        'tpl_suffix' => '.html',
        'attr'       => 'php-',
        'max_tag'    => 10000,
    );
    private $tpl_var   = array();
    private $tpl_file  = '';
    private $tpl_block = '';

    /**
     * 架构函数
     */
    public function __construct($config) {
        $this->config = array_merge($this->config, $config);
    }

    /**
     * 分配模板变量
     * @param string $name 模板变量
     * @param mixed $value 值
     */
    public function assign($name, $value = null) {
        if (is_array($name)) {
            $this->tpl_var = array_merge($this->tpl_var, $name);
        } else {
            $this->tpl_var[$name] = $value;
        }
    }

    /**
     * 编译模板
     * @param string $tpl_file 模板文件
     * @param array $tpl_var 模板变量
     */
    public function fetch($tpl_file, $tpl_var = array()) {
        $content = $this->compiler($tpl_file, $tpl_var);
        // 模板阵列变量分解成为独立变量
        if (!is_null($this->tpl_var)) {
            extract($this->tpl_var, EXTR_OVERWRITE);
        }
        // 页面缓存
        ob_start();
        ob_implicit_flush(0);
        eval('?>' . $content);
        // 获取并清空缓存
        $content = ob_get_clean();
        return $content;
    }

    /**
     * 编译模板并输出执行结果
     * @param string $tpl_file 模板文件
     * @param array $tpl_var 模板变量
     */
    public function display($tpl_file, $tpl_var = array()) {
        echo $this->fetch($tpl_file, $tpl_var);
    }

    /**
     * 编译模板内容
     * @param string $tpl_content 模板内容
     * @return string 编译后的php混编代码
     */
    public function compiler($tpl_file, $tpl_var = array()) {
        if ($tpl_var) {
            $this->tpl_var = array_merge($this->tpl_var, $tpl_var);
        }
        if (strpos($tpl_file, $this->config['tpl_suffix']) > 0) {
            $this->tpl_file = $tpl_file;
        } else if (is_file($this->config['tpl_path'] . $tpl_file . $this->config['tpl_suffix'])) {
            $this->tpl_file = $this->config['tpl_path'] . $tpl_file . $this->config['tpl_suffix'];
            $tpl_file       = $this->tpl_file;
        }
        if (is_file($tpl_file)) {
            $tpl_content = file_get_contents($tpl_file);
        } else {
            $tpl_content = $tpl_file;
        }
        //模板解析
        $tpl_content = $this->parse($tpl_content);
        // 优化生成的php代码
        /* $tpl_content = str_replace('?><?php', '', $tpl_content); */
        return $tpl_content;
    }

    /**
     * 解析模板标签属性
     * @param string $content 要模板代码
     * @return string 解析后的模板代码
     */
    public function parse($content) {
        $num = $this->config['max_tag'];
        while (true) {
            $sub = $this->match($content);
            if ($sub) {
                $method = 'parse' . $sub['attr'];
                if (method_exists($this, $method)) {
                    $content = $this->$method($content, $sub);
                } else {
                    throw new Exception("模板属性" . $this->config['attr'] . $sub['attr'] . '没有对应的解析规则');
                    break;
                }
            } else {
                break;
            }
            if ($num-- <= 0) {
                throw new Exception('解析出错, 超过了最大属性数');
            }
        }
        $content = $this->parseValue($content);
        return $content;
    }

    /**
     * 解析include属性
     * @param string $content 源模板内容
     * @param array $match 一个正则匹配结果集, 包含 html, value, attr
     * @return string 解析后的模板内容
     */
    private function parseInclude($content, $match) {
        $tpl_name = $match['value'];
        if (substr($tpl_name, 0, 1) == '$') {
            //支持加载变量文件名
            $tpl_name = $this->get(substr($tpl_name, 1));
        }
        $array     = explode(',', $tpl_name);
        $parse_str = '';
        foreach ($array as $tpl) {
            if (empty($tpl))
                continue;
            if (false === strpos($tpl, $this->config['tpl_suffix'])) {
                // 解析规则为 模块@主题/控制器/操作
                $tpl = $this->parseTemplateFile($tpl);
            }
            if (file_exists($tpl)) {
                // 获取模板文件内容
                $parse_str .= file_get_contents($tpl);
            } else {
                $parse_str .= '模板文件不存在: ' . $tpl;
            }
        }
        return str_replace($match['html'], $parse_str, $content);
    }

    /**
     * 处理include的模板路径
     * @param string $tpl 模板路径
     * @return string 模板的真实地址
     */
    private function parseTemplateFile($tpl) {
        if (strpos($tpl, $this->config['tpl_suffix'])) {
            return $tpl;
        } else {
            if (strpos($tpl, '/')) {
                return $this->config['tpl_path'] . $tpl . $this->config['tpl_suffix'];
            } else {
                return dirname($this->tpl_file) . '/' . $tpl . $this->config['tpl_suffix'];
            }
        }
    }

    /**
     * 解析init属性
     * @return string 解析后的模板内容
     */
    private function parseInit($content, $match) {
        $new = "<?php {$match['value']}; ?>";
        $new .= str_replace($match['exp'], '', $match['html']);
        return str_replace($match['html'], $new, $content);
    }

    /**
     * 解析init属性
     * @return string 解析后的模板内容
     */
    private function parseExec($content, $match) {
        $new = "<?php {$match['value']}; ?>";
        $new .= str_replace($match['exp'], '', $match['html']);
        return str_replace($match['html'], $new, $content);
    }

    /**
     * 解析if属性
     * @return string 解析后的模板内容
     */
    private function parseIf($content, $match) {
        $new = "<?php if ({$match['value']}) { ?>";
        $new .= str_replace($match['exp'], '', $match['html']);
        $new .= '<?php } ?>';
        return str_replace($match['html'], $new, $content);
    }

    /**
     * 解析switch属性
     * @return string 解析后的模板内容
     */
    private function parseSwitch($content, $match) {
        $start = "<?php switch ({$match['value']}) { ?>";
        $end   = "<?php } ?>";
        $new   = preg_replace('/^[^>]*>/', $start, $match['html']);
        $new   = preg_replace('/<[^<]*$/', $end, $new);
        $new   = str_replace($match['html'], $new, $content);
        return $new;
    }

    /**
     * 解析case属性
     * @return string 解析后的模板内容
     */
    private function parseCase($content, $match) {
        $new = "<?php case {$match['value']}: ?>";
        $new .= str_replace($match['exp'], '', $match['html']);
        $new .= '<?php break; ?>';
        return str_replace($match['html'], $new, $content);
    }

    /**
     * 解析defalut属性
     * @return string 解析后的模板内容
     */
    private function parseDefault($content, $match) {
        $new = "<?php default: ?>";
        $new .= str_replace($match['exp'], '', $match['html']);
        $new .= '<?php break; ?>';
        return str_replace($match['html'], $new, $content);
    }

    /**
     * 解析repeat属性
     * @return string 解析后的模板内容
     */
    private function parseRepeat($content, $match) {
        $new = "<?php foreach ({$match['value']}) { ?>";
        $new .= str_replace($match['exp'], '', $match['html']);
        $new .= '<?php } ?>';
        return str_replace($match['html'], $new, $content);
    }

    /**
     * 解析foreach属性
     * @return string 解析后的模板内容
     */
    private function parseForeach($content, $match) {
        $new = "<?php foreach ({$match['value']}) { ?>";
        $new .= str_replace($match['exp'], '', $match['html']);
        $new .= '<?php } ?>';
        return str_replace($match['html'], $new, $content);
    }

    /**
     * 解析for属性
     * @return string 解析后的模板内容
     */
    private function parseFor($content, $match) {
        $new = "<?php for ({$match['value']}) { ?>";
        $new .= str_replace($match['exp'], '', $match['html']);
        $new .= '<?php } ?>';
        return str_replace($match['html'], $new, $content);
    }

    /**
     * 解析show属性
     * @return string 解析后的模板内容
     */
    private function parseShow($content, $match) {
        $new = "<?php if ({$match['value']}) { ?>";
        $new .= str_replace($match['exp'], '', $match['html']);
        $new .= '<?php } ?>';
        return str_replace($match['html'], $new, $content);
    }

    /**
     * 解析hide属性
     * @return string 解析后的模板内容
     */
    private function parseHide($content, $match) {
        $new = "<?php if (!({$match['value']})) { ?>";
        $new .= str_replace($match['exp'], '', $match['html']);
        $new .= '<?php } ?>';
        return str_replace($match['html'], $new, $content);
    }

    /**
     * 解析before属性
     * @return string 解析后的模板内容
     */
    private function parseBefore($content, $match) {
        $new = "<?php {$match['value']}; ?>";
        $new .= str_replace($match['exp'], '', $match['html']);
        return str_replace($match['html'], $new, $content);
    }

    /**
     * 解析after属性
     * @return string 解析后的模板内容
     */
    private function parseAfter($content, $match) {
        $new = str_replace($match['exp'], '', $match['html']);
        $new .= "<?php {$match['value']}; ?>";
        return str_replace($match['html'], $new, $content);
    }

    /**
     * 解析function属性
     * @return string 解析后的模板内容
     */
    private function parseFunction($content, $match) {
        $new = "<?php function {$match['value']} { ?>";
        $new .= str_replace($match['exp'], '', $match['html']);
        $new .= '<?php } ?>';
        return str_replace($match['html'], $new, $content);
    }

    /**
     * 解析调用function属性
     * @return string 解析后的模板内容
     */
    private function parseCall($content, $match) {
        $new = "<?php {$match['value']}; ?>";
        return str_replace($match['html'], $new, $content);
    }

    /**
     * 解析模板继承
     * @return string
     */
    private function parseExtends($content, $match) {
        $this->tpl_block .= $content;
        $content         = 'extends';
        $match['html']   = $content;
        $content         = $this->parseInclude($content, $match);
        return $content;
    }

    /**
     * 解析继承的代码块
     * @return string
     */
    private function parseBlock($content, $match) {
        $block = $this->match($this->tpl_block, 'block', $match['value']);
        if ($block) {
            $new = str_replace($block['exp'], '', $block['html']);
            return str_replace($match['html'], $new, $content);
        } else {
            $new = str_replace($match['exp'], '', $match['html']);
            return str_replace($match['html'], $new, $content);
        }
    }

    /**
     * 解析普通变量和函数{$title}{:function_name($var)}
     * @param string $content 源模板内容
     * @return string 解析后的模板内容
     */
    private function parseValue($content) {
        // {$vo.name} to {$vo["name"]}
        $content = preg_replace('/\{(\$[\w\[\"\]]*)\.(\w*)(.*)\}/', '{\1["\2"]\3}', $content);
        $content = preg_replace('/\{(\$[\w\[\"\]]*)\.(\w*)(.*)\}/', '{\1["\2"]\3}', $content);
        // {$var??'xxx'} to {$var?$var:'xxx'}
        $content = preg_replace('/\{(\$.*?)\?\s*\?(.*)\}/', '{\1?\1:\2}', $content);
        // {$var?='xxx'} to {$var?'xxx':''}
        $content = preg_replace('/\{(\$.*?)\?\=(.*)\}/', '{\1?\2:""}', $content);
        $content = preg_replace('/\{(\$.*?)\}/', '<?php echo \1; ?>', $content);
        $content = preg_replace('/\{\:(.*?)\}/', '<?php echo \1; ?>', $content);
        // 合并php代码结束符号和开始符号
        $content = preg_replace('/\?>[\s\n]*<\?php/', '', $content);
        return $content;
    }

    /**
     * 获取第一个表达式
     * @param string $content 要解析的模板内容
     * @param string $attr 属性名
     * @param string $val 属性值
     * @return array 一个匹配的标签数组
     */
    private function match($content, $attr = '[\w]+', $val = '[^\4]*?') {
        $reg   = '#<(?<tag>[\w]+)[^>]*?\s(?<exp>'
                . preg_quote($this->config['attr'])
                . '(?<attr>' . $attr
                . ')=([\'"])(?<value>' . $val . ')\4)[^>]*>#s';
        $match = null;
        if (!preg_match($reg, $content, $match)) {
            return null;
        }
        $sub = $match[0];
        $tag = $match['tag'];
        /* 如果是单标签, 就直接返回 */
        if (substr($sub, -2) == '/>') {
            $match['html'] = $match[0];
            return $match;
        }
        /* 查找完整标签 */
        $start_tag_len   = strlen($tag) + 1; // <div
        $end_tag_len     = strlen($tag) + 3;   // </div>
        $start_tag_count = 0;
        $content_len     = strlen($content);
        $pos             = strpos($content, $sub);
        $start_pos       = $pos + strlen($sub);
        while ($start_pos < $content_len) {
            $is_start_tag = substr($content, $start_pos, $start_tag_len) == '<' . $tag;
            $is_end_tag   = substr($content, $start_pos, $end_tag_len) == "</$tag>";
            if ($is_start_tag) {
                $start_tag_count++;
            }
            if ($is_end_tag) {
                $start_tag_count--;
            }
            if ($start_tag_count < 0) {
                $match['html'] = substr($content, $pos, $start_pos - $pos + $end_tag_len);
                return $match;
            }
            $start_pos++;
        }
        return null;
    }

}
