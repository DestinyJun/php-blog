<?php
/**
 * Class BaseController 基础控制器类
 */
namespace Frame\Libs {
  use Frame\Vendor\Smarty;
  abstract class BaseController
  {
    protected $smarty = null;
    public function __construct()
    {
      $this->initSmarty();
    }
    // 初始化smarty对象
    private function initSmarty() {
      $this->smarty = new Smarty();
      $this->smarty->left_delimiter = "{{"; // smarty的左定界符
      $this->smarty->right_delimiter = "}}"; // smarty的右定界符
      $this->smarty->setTemplateDir(VIEW_PATH); // 配置视图文件的工作目录
      $this->smarty->setCompileDir(sys_get_temp_dir().DS."view".DS); // 配置编译目录，sys_get_temp_dir获取PHP在操作系统系统中的临时缓存目录
    }
    // 受保护的跳转方法
    protected function jump($message,$url='?',$timer=3) {
      $this->smarty->assign('message',$message);
      $this->smarty->assign('url',$url);
      $this->smarty->assign('time',$timer);
      $this->smarty->display('Public/jump.html');
      header("refresh:{$timer};url={$url}");
      die(); // 防止代码继续执行，导致跳转失效
    }
    // 判断用户是否登陆
    protected function auth()
    {
      if (!isset($_SESSION['username'])) {
        $this->jump('请您先登陆！','?c=User&a=login');
      }
    }
  }
}

