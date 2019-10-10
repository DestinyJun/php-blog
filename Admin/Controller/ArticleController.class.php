<?php
namespace Admin\Controller {
  use Admin\Model\ArticleModel;
  use Admin\Model\CategoryModel;
  use Frame\Libs\BaseController;
  use Frame\Vendor\Pager;

  final class ArticleController extends BaseController
  {
    public function Index()
    {
      $this->auth();
      $articleModel = ArticleModel::getInstance();
      // （1）构建查询赛选
      $where = "2>1 "; // 初始查询条件
      if (!empty($_REQUEST['category_id'])) { // 增加按照文章分类查询
        $where .= "AND category_id=" . $_REQUEST['category_id'];
      }
      if (!empty($_REQUEST['keyword'])) { // 构建按照title进行模糊匹配查询
        $where .= " AND title like '%" . $_REQUEST['keyword'] . "%'";
      }
      // （2）查询文章分类数据
      $categoryModel = CategoryModel::getInstance();
      $category_list = $categoryModel->fetchTree($categoryModel->fetchAll());
      // （3）构建分页的参数
      $pagesize = 10;
      $page = isset($_GET['page']) ? $_GET['page'] : 1;
      $records = $articleModel->fetchCount($where);
      $params = array(
        'c' => 'Article',
        'a' => 'Index'
      );
      $startTrow = ($page - 1) * $pagesize;
      // （4）添加条件查询参数到分页上
      if (!empty($_REQUEST['category_id'])) {
        $params['category_id'] = $_REQUEST['category_id'];
      }
      if (!empty($_REQUEST['keyword'])) {
        $params['keyword'] = $_REQUEST['keyword'];
      }
      // （3）获取连表查询的文章数据
      $article_list = $articleModel->fetchAllWithJoin($where, $startTrow, $pagesize);
      // （4）创建分页类对象
      $pageObj = new Pager($records, $pagesize, $page, $params);
      // 向视图赋值
      $this->smarty->assign(array(
        'category_list' => $category_list,
        'article_list' => $article_list,
        'page_list' => $pageObj->showPage(),
      ));
      $this->smarty->display('Article/Index.html');
    }

    public function add()
    {
      $this->auth();
      // （1）查询文章分类数据
      $categoryModel = CategoryModel::getInstance();
      $category_list = $categoryModel->fetchTree($categoryModel->fetchAll());
      $this->smarty->assign('category_list', $category_list);
      $this->smarty->display('Article/add.html');
    }

    public function insert()
    {
      $this->auth();
      $arr['category_id'] = $_POST['category_id'];
      $arr['title'] = $_POST['title'];
      $arr['top'] = $_POST['top'];
      $arr['content'] = $_POST['content'];
      $arr['orderby'] = $_POST['orderby'];
      $arr['addate'] = time();
      $arr['user_id'] = $_SESSION['uid'];
      $articleModel = ArticleModel::getInstance();
      if ($articleModel->fetchInsert($arr)) {
        $this->jump("添加成功！", '?c=Article&a=Index');
      } else {
        $this->jump("添加失败！", '?c=Article&a=add');
      }
    }

    public function delete()
    {
      $this->auth();
      $id = $_GET['id'];
      $categoryModel = ArticleModel::getInstance();
      if ($categoryModel->fetchDelete($id)) {
        $this->jump('删除成功', '?c=Category&a=Index ');
      }
    }

    public function edit()
    {
      $this->auth();
      // （1）查询文章分类数据
      $categoryModel = CategoryModel::getInstance();
      $category_list = $categoryModel->fetchTree($categoryModel->fetchAll());
      // （2）查询单条文章数据
      $articleModel = ArticleModel::getInstance();
      $id = $_GET['id'];
      $article = $articleModel->fetchOne("id = {$id}");
      // （3）向模板赋值
      $this->smarty->assign(array(
        'category_list' =>$category_list,
        'article' =>$article,
      ));
      $this->smarty->display('Article/edit.html');
    }

    public function update()
    {
      $this->auth();
      $arr['category_id'] = $_POST['category_id'];
      $arr['title'] = $_POST['title'];
      $arr['top'] = $_POST['top'];
      // 过滤特殊符号，保证安全
      $arr['content'] = addslashes($_POST['content']);
      $arr['orderby'] = $_POST['orderby'];
      $articleModel = ArticleModel::getInstance();
      if( $articleModel->fetchUpdate($arr,$_POST['id'])) {
        $this->jump("修改成功！", '?c=Article&a=Index');
      } else {
        $this->jump("修改失败！", '?c=Article&a=add');
      }
     ;
    }
  }
}
