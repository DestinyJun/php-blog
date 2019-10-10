<?php
namespace Admin\Controller{
  use Admin\Model\CategoryModel;
  use Frame\Libs\BaseController;
  final class CategoryController extends BaseController{
    public function Index()
    {
      $this->auth();
      $categoryModel = CategoryModel::getInstance();
      $category_list = $categoryModel->fetchTree($categoryModel->fetchAll());
    /*  echo '<pre>';
      print_r($category_list);
      die();*/
      // 获取无限级分类数组
      $this->smarty->assign('category_list', $category_list);
      $this->smarty->display('Category/Index.html');
    }
    public function add()
    {
      $this->auth();
      $categoryModel = CategoryModel::getInstance();
      $category_list = $categoryModel->fetchTree($categoryModel->fetchAll());
      $this->smarty->assign('category_list', $category_list);
      $this->smarty->display('Category/add.html');
    }
    public function insert()
    {
      $this->auth();
      $arr['classname'] = $_POST['classname'];
      $arr['orderby'] = $_POST['orderby'] ;
      $arr['pid'] = $_POST['pid'];
      $categoryModel = CategoryModel::getInstance();
      if ($categoryModel->fetchInsert($arr)) {
        $this->jump("添加成功！", '?c=Category&a=Index');
        die();
      } else {
        $this->jump("添加失败！", '?c=Category&a=add');
        die();
      }
    }

    public function delete()
    {
     /* $this->auth();
      $id = $_GET['id'];
      $categoryModel = CategoryModel::getInstance();
      $categoryModel->fetchTreeIdOne($id);*/
      $this->auth();
      $id = $_GET['id'];
      $categoryModel = CategoryModel::getInstance();
      if ($categoryModel->fetchDelete($id)) {
        $this->jump('删除成功', '?c=Category&a=Index ');
      }
    }
  }
}
