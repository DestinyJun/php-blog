<?php
namespace Admin\Controller{
  use Admin\Model\LineModel;
  use Frame\Libs\BaseController;
  final class LineController extends BaseController{
    public function Index()
    {
      $this->auth();
      $lineModel = LineModel::getInstance();
      $lines_list = $lineModel->fetchAll();
      $this->smarty->assign('lines_list', $lines_list);
      $this->smarty->display('Line/Index.html');
    }
  }
}
