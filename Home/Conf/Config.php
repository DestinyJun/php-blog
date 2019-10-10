<?php
/**
 * 前台配置文件
 */
return array(
  // 数据库配置
  'db_type'=>'mysql',// 数据库剋新
  'db_host'=>'127.0.0.1',// 主机名
  'db_port'=>'3306',// 端口
  'db_name'=>'blog',//数据库名
  'db_user'=>'root',//用户名
  'db_pass'=>'root',//密码
  'db_charset'=>'utf8',//字符集
  // 前台应用默认路由参数
  'default_platform'=>'Home', // 默认应用
  'default_controller'=>'Index',// 默认控制器
  'default_action'=>'Index',// 默认动作
);
