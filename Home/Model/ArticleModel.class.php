<?php
/**
 * 文章模型类
 */
namespace Home\Model{

  use Frame\Libs\BaseModel;

  final class ArticleModel extends BaseModel
  {
    protected $table = 'article';

    // 按照月份归档文章
    public function fetchAllMonth()
    {
      $sql = "SELECT date_format(from_unixtime(addate),'%Y年%m月') AS months,count(id) AS records ";
      $sql .= "FROM {$this->table} ";
      $sql .= "GROUP BY months ";
      $sql .= "ORDER BY months DESC";
      return $this->pdoPro->fetchAll($sql);
    }

    // 查询所有文章带作者昵称
    public function fetchAllArticleName($where="2>1",$startTrow=0,$pagesize=10)
    {
      $sql = "SELECT article.*,user.nikename,category.classname FROM {$this->table} ";
      $sql .= "LEFT JOIN user ON article.user_id=user.id ";
      $sql .= "LEFT JOIN category ON article.category_id=category.id ";
      $sql .= "WHERE {$where} ";
      $sql .= "ORDER BY article.id DESC ";
      $sql .= "LIMIT {$startTrow},{$pagesize}";
      return $this->pdoPro->fetchAll($sql);
    }

    // 指定id查询单一文章内容
    public function fetchOneArticle($where="2>1",$order="article.id DESC")
    {
      $sql = "SELECT article.*,user.nikename,category.classname FROM {$this->table} ";
      $sql .= "LEFT JOIN user ON article.user_id=user.id ";
      $sql .= "LEFT JOIN category ON article.category_id=category.id ";
      $sql .= "WHERE {$where} ";
      $sql .= "ORDER BY {$order}";
      return $this->pdoPro->fetchOne($sql);
    }

    // 更新阅读数
    public function updateRead($id)
    {
      $sql="UPDATE {$this->table} SET `read`=`read`+1 WHERE id={$id}";
      return $this->pdoPro->exec($sql);
    }

    // 更新点赞数
    public function updatePraise($id)
    {
      $sql="UPDATE {$this->table} SET `praise`=`praise`+1 WHERE id={$id}";
      return $this->pdoPro->exec($sql);
    }
  }
}

