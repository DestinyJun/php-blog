<?php
namespace Admin\Model{
  use Frame\Libs\BaseModel;
  final class ArticleModel extends BaseModel
  {
    protected $table = 'article';

    //单独一个特殊方法——连表查询
    public function fetchAllWithJoin($where="2>1",$startTrow=0,$pagesize=10)
    {
      // （1）构建连表查咨的SQL语句
      $sql="SELECT article.*,category.classname,user.nikename FROM {$this->table} ";
      $sql.="LEFT JOIN category ON article.category_id=category.id ";
      $sql.="LEFT JOIN user ON article.user_id=user.id ";
      $sql.="WHERE {$where} ";
      $sql.="ORDER BY article.id DESC ";
      $sql.="LIMIT {$startTrow},{$pagesize}";
      // （2）执行sql语句，返回结果
      return $this->pdoPro->fetchAll($sql);
    }
  }
}

