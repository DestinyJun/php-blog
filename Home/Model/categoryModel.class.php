<?php
/**
 * 前台分类模块
 */
namespace Home\Model{

  use Frame\Libs\BaseModel;

  final class categoryModel extends BaseModel
  {
    protected $table = 'category';
    // 获取带文章数的文章分类数据（需要连表查询）
    public function fetchAllWithCount()
    {
      // 构建连表查询的语句
      $sql = "SELECT category.*,count(article.id) as records FROM {$this->table} ";
      $sql .= "LEFT JOIN article ON category.id=article.category_id ";
      $sql .= "WHERE pid = 0 GROUP BY category.id";
      return $this->pdoPro->fetchAll($sql);
    }

    // 无限级分类方法
    public function fetchTree($arrs,$level=0,$pid=0)
    {
      static $tree = array();
      foreach ($arrs as $arr) {
        if ($arr['pid'] == $pid) {
          $arr['level'] = $level;
          $tree[] = $arr;
          // 递归调用
          $this->fetchTree($arrs,$level+1,$arr['id']);
        }
      }
      return $tree;
    }
  }
}

