<?php
namespace Admin\Model{
  use Frame\Libs\BaseModel;
  final class CategoryModel extends BaseModel
  {
    protected $table = 'category';

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

    // 无限级获取方法
    public function fetchTreeIdOne($id)
    {
      static $arr1 = array();
      $sql = "select * from category where id={$id}";
      $data = $this->pdoPro->fetchAll($sql);
      $arr1[] = $data;
      $arr = $this->fetchTreeIdTwo($id);
      $arr1[] = $arr;
      foreach ($arr as $item) {
        $arr1[] = $this->fetchTreeIdTwo($item['id']);
      }
      return $arr1;
    }
    public function fetchTreeIdTwo($id)
    {
      $sql = "select * from category where pid={$id}";
      $data = $this->pdoPro->fetchAll($sql);
      return $data;
    }
  }
}

