<?php
namespace Admin\Model {
  use Frame\Libs\BaseModel;
  class IndexModel extends BaseModel
  {
    public function fetchAll() {
      $sql_all = "SELECT * FROM user ORDER BY id DESC";
      return $this->pdoPro->fetchAll($sql_all);
    }
  }
}

