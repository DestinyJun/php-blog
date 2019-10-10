<?php
/**
 * Index控制器
 */
namespace Home\Controller {
  use Frame\Libs\BaseController;
  use Frame\Vendor\Pager;
  use Home\Model\ArticleModel;
  use Home\Model\categoryModel;
  use Home\Model\LinksModel;
  final class IndexController extends BaseController
  {
    public function Index() {
      // 查询友情链接
      $linksObj = LinksModel::getInstance();
      $links = $linksObj->fetchAll();

      // 获取无线级文章分类
      $categoryObj = categoryModel::getInstance();
      $categoryList = $categoryObj->fetchTree($categoryObj->fetchAllWithCount());

      // 获取文章按照月份归档数据
      $articleObj = ArticleModel::getInstance();
      $articleMonth = $articleObj->fetchAllMonth();

      /**
       * 查询首页文章
       */
      // （1）构建查询赛选
      $where = "2>1 "; // 初始查询条件
      if (!empty($_REQUEST['keyword'])) { // 构建按照title进行模糊匹配查询
        $where .= " AND title like '%" .$_REQUEST['keyword']."%'";
      }
      if (!empty($_REQUEST['category_id'])) { // 构建按照分类查询
        $where .= " AND category_id =" .$_REQUEST['category_id'];
      }
      // （2）构建分页参数
      $pagesize = 5;
      $page = isset($_GET['page']) ? $_GET['page'] : 1;
      $records = $articleObj->fetchCount($where);
      $params = array(
        'c' => 'Index',
        'a' => 'Index'
      );
      $startTrow = ($page - 1) * $pagesize;
      // （3）添加条件查询参数到分页上
      if (!empty($_REQUEST['keyword'])) {
        $params['keyword'] = $_REQUEST['keyword'];
      }
      if (!empty($_REQUEST['category_id'])) {
        $params['category_id'] = $_REQUEST['category_id'];
      }
      // （4）查询文章数据
      $articleList = $articleObj->fetchAllArticleName($where,$startTrow, $pagesize);
      // （5）创建分页类对象，显示分页
      $pageObj = new Pager($records,$pagesize,$page,$params);


      // 赋值视图并显示
      $this->smarty->assign(array(
        'links' => $links,
        'categorys' => $categoryList,
        'articleMonth' => $articleMonth,
        'articleList' => $articleList,
        'page_list' => $pageObj->showPage(),
      ));
      $this->smarty->display('Index/Index.html');
    }

    public function showList()
    {
      // 获取无线级文章分类
      $categoryObj = categoryModel::getInstance();
      $categoryList = $categoryObj->fetchTree($categoryObj->fetchAllWithCount());

      // 获取文章按照月份归档数据
      $articleObj = ArticleModel::getInstance();
      $articleMonth = $articleObj->fetchAllMonth();

      // 查询友情链接
      $linksObj = LinksModel::getInstance();
      $links = $linksObj->fetchAll();

      // （1）构建查询赛选
      $where = "2>1 "; // 初始查询条件
      if (!empty($_REQUEST['keyword'])) { // 构建按照title进行模糊匹配查询
        $where .= " AND title like '%" .$_REQUEST['keyword']."%'";
      }
      if (!empty($_REQUEST['category_id'])) { // 构建按照分类查询
        $where .= " AND category_id =" .$_REQUEST['category_id'];
      }
      // （2）构建分页参数
      $pagesize = 30;
      $page = isset($_GET['page']) ? $_GET['page'] : 1;
      $records = $articleObj->fetchCount($where);
      $params = array(
        'c' => 'Index',
        'a' => 'Index'
      );
      $startTrow = ($page - 1) * $pagesize;
      // （3）添加条件查询参数到分页上
      if (!empty($_REQUEST['keyword'])) {
        $params['keyword'] = $_REQUEST['keyword'];
      }
      if (!empty($_REQUEST['category_id'])) {
        $params['category_id'] = $_REQUEST['category_id'];
      }
      // （4）查询文章数据
      $articleList = $articleObj->fetchAllArticleName($where,$startTrow, $pagesize);
      // （5）创建分页类对象，显示分页
      $pageObj = new Pager($records,$pagesize,$page,$params);

      // 赋值视图并显示
      $this->smarty->assign(array(
        'links' => $links,
        'categorys' => $categoryList,
        'articleMonth' => $articleMonth,
        'articleList' => $articleList,
        'page_list' => $pageObj->showPage(),
      ));
      $this->smarty->display('Index/list.html');
    }

    public function content()
    {
      // 查询友情链接
      $linksObj = LinksModel::getInstance();
      $links = $linksObj->fetchAll();

      // 获取无线级文章分类
      $categoryObj = categoryModel::getInstance();
      $categoryList = $categoryObj->fetchTree($categoryObj->fetchAllWithCount());

      // 获取文章按照月份归档数据
      $articleObj = ArticleModel::getInstance();
      $articleMonth = $articleObj->fetchAllMonth();

      $id = $_REQUEST['id'];
      // 修改阅读数，先修改，后读取
      $articleObj->updateRead($id);

      // 根据ID查询一条文章内容
      $articleContent = $articleObj->fetchOneArticle("article.id={$id}");

      // 查询前一篇文章跟后一篇文章
      $articlePrev = $articleObj->fetchOneArticle("article.id<{$id}","article.id DESC");
      $articleNext = $articleObj->fetchOneArticle("article.id>{$id}","article.id ASC");
      // 赋值视图并显示
      $this->smarty->assign(array(
        'links' => $links,
        'categorys' => $categoryList,
        'articleMonth' => $articleMonth,
        'articleContent' => $articleContent,
        'articlePrev' => $articlePrev,
        'articleNext' => $articleNext,
      ));
      $this->smarty->display('Index/content.html');
    }

    public function praise()
    {
      $id = $_REQUEST['id'];
      // 先登陆，才能点赞
      if (!isset($_SESSION['username'])) {
        $this->jump('请您先登陆！','./Admin.php?c=User&a=login');
      } else {
        // 判断文章是否被点赞过
        if(!isset($_SESSION['praise'][$id])) {
          // 更改当前ID的状态值
          $_SESSION['praise'][$id] = 1;
          // 更新点赞数
          $articleObj = ArticleModel::getInstance();
          $articleObj->updatePraise($id);
          $this->jump('感谢您点赞！',"?c=Index&a=content&id={$id}");
        } else {
          // 如果被点赞过，不能再次点赞
          $this->jump('您已经点赞过了，不能重复点赞！',"?c=Index&a=content&id={$id}");
        }
      }
    }
  }
}
