<?php
namespace Admin\Controller{
//  use \Admin\Model\IndexModel;
  use \Frame\Libs\BaseController;
  final class IndexController extends BaseController
  {
    public function Index() {
      $this->auth();
//      $modelObj = IndexModel::getInstance();
//      print_r($modelObj->fetchAll());
//      $this->smarty->assign('Str','我是后台');
      $this->smarty->display('Index/Index.html');
    }
  }
}
