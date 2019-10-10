<?php
/**
 * Class Smarty 扩展smarty类
 */
namespace Frame\Vendor {
  require_once (VENDOR_PATH."smarty".DS."libs".DS."Smarty.class.php");
  // Frame\Vendor这个虚拟的命名空间下并内有Smarty类，而自动加载只能加载命名空间的类，因此只能手动包含
  final class Smarty extends \Smarty
  {
    // 什么都不用做，只需要继承即可，这样就能使用命名空间了
  }
}
