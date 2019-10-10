<?php

/**
 * 初始化类
 */
namespace Frame {
  class Frame
  {
    public  static function run()
    {
      self::initCharset();  // 初始化字符集设置
      self::initConfig();  // 初始化配置信息
      self::initRoute();   // 初始化路由参数
      self::initConst();   // 初始化目录常量设置
      self::initAutoLoad();   // 初始化类的自动加载
      self::initDispatch();   // 初始化请求分发
    }
    private static function initCharset()
    {
      // 初始化字符集
      header("content-type:text/html;charset=utf8");
      // 开启session
      session_start();
    }
    private static function initConfig()
    {
      $GLOBALS["config"] = require_once (APP_PATH."Conf".DS."Config.php");
    }
    private static function initRoute()
    {
      // 获取应用的平台、控制器名称、动作参数，也即是路由
      $platform  = $GLOBALS['config']['default_platform'];
      $controller  = isset($_GET['c'])?$_GET['c']:$GLOBALS['config']['default_controller'];
      $action = isset($_GET['a'])?$_GET['a']:$GLOBALS['config']['default_action'];
      define('PLATFORM',$platform);            // 控制器常量
      define('CONTROLLER',$controller);          // 控制器常量
      define('ACTION',$action);             // 行为常量
    }
    private static function initConst()
    {
      // 常用的目录常量设置
      define('CONTROLLER_PATH',APP_PATH."Controller".DS);  // Controller目录
      define('MODEL_PATH',APP_PATH."Model".DS);  //Model目录
      define('VIEW_PATH',APP_PATH."View".DS);  //View目录
      define('VENDOR_PATH',ROOT_PATH."Frame".DS."Vendor".DS);  //Vendor目录
      define('PUBLIC_PATH',ROOT_PATH."Public".DS);  //Public目录
      define('ADMIN_FONT_PATH',str_replace('\\','/',PUBLIC_PATH).'Admin/fonts/');  //验证码字体目录
    }
    private static function initAutoLoad()
    {
      //包含所有类文件进行自动加载
      spl_autoload_register(function ($className) {
//        echo ROOT_PATH."Frame".DS."Libs".DS."$className.class.php<br>";
        $arr  = [
          ROOT_PATH."Frame".DS."Libs".DS."$className.class.php",
          ROOT_PATH.$className.".class.php"
        ];
        foreach ($arr as $fileName) {
          if (file_exists($fileName)) {
            require_once ($fileName);
          }
        }
      });
    }
    private static function initDispatch()
    {
      // 反正其核心本质就是找到控制器，就能找到一切，项目就能跑起来，这就是mvc的精髓
      // 根据用户动作执行不同新闻的代码
      $className = "\\".PLATFORM.DS."Controller"."\\".CONTROLLER."Controller"; // 拼接控制器名称
      $ctrlObj = new $className(); // new一个控制器对象
      $actionName = ACTION; // 拼接成员函数名
      $ctrlObj->$actionName(); // 函数名称只能是一个变量，所以这里需要把常量转换为变量才能用作函数名
    }
  }
}
