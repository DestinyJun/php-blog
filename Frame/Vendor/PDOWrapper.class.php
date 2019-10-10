<?php
namespace Frame\Vendor {
  use \PDO; // 引入全局PDO类
  use \PDOException; // 引入全局PDO异常类

  final class PDOWrapper
  {
    // 数据库配置属性
    private $db_type;
    private $db_host;
    private $db_port;
    private $db_name;
    private $db_user;
    private $db_pass;
    private $db_charset;
    private $pdo = null; // 保存PDO对象
    public function __construct()
    {
      $this->db_type = $GLOBALS['config']['db_type'];
      $this->db_host = $GLOBALS['config']['db_host'];
      $this->db_port = $GLOBALS['config']['db_port'];
      $this->db_name = $GLOBALS['config']['db_name'];
      $this->db_user = $GLOBALS['config']['db_user'];
      $this->db_pass = $GLOBALS['config']['db_pass'];
      $this->db_charset = $GLOBALS['config']['db_charset'];
      $this->connectDb(); // 创建PDO对象、连接数据库、选择数据库
      $this->setErrMode(); // 设置PDO错误处理模式
    }
    // 创建PDO对象、连接数据库、选择数据库
    private function connectDb () {
      try {
        // 这里加'\'的原因数PDO是系统类，只能从根目录中找到，不加就相当于再当前命名空间中找PDO，那肯定是找不大的
        $dsn = "{$this->db_type}:host={$this->db_host};port={$this->db_port};dbname={$this->db_name};charset={$this->db_charset}";
        $this->pdo = new PDO($dsn,$this->db_user,$this->db_pass);
      } catch (PDOException $err) {
        echo "<h3>创建PDO对象出错！</h3>";
        echo "错误状态码：".$err->getCode()."<br>";
        echo "错误行号：".$err->getLine()."<br>";
        echo "错误信息：".$err->getMessage()."<br>";
        echo "错误文件：".$err->getFile()."<br>";
      }
   }
    // 设置PDO错误处理模式
    private function setErrMode () {
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }
    // 公共的执行SQL的insert、update、add语句的方法
    public function exec($sql) {
      try {
        return $this->pdo->exec($sql);
      } catch (PDOException $err){
       $this->errorHandle($err);
      }
    }
    // PDO返回一条数据
    public function fetchOne($sql) {
      try {
        $PDOStatement = $this->pdo->query($sql);
        return $PDOStatement->fetch(2);
      } catch (PDOException $err){
        $this->errorHandle($err);
      }
    }
    //  PDO返回多条数据
    public function fetchAll($sql) {
      try {
        $PDOStatement = $this->pdo->query($sql);
        return $PDOStatement->fetchAll(2);
      } catch (PDOException $err){
        $this->errorHandle($err);
      }
    }
    //  PDO返回数据记录条数
    public function fetchRecord($sql) {
      try {
        $PDOStatement = $this->pdo->query($sql);
        return $PDOStatement->rowCount();
      } catch (PDOException $err){
        $this->errorHandle($err);
      }
    }
    // 私有的错误处理方法
    private function errorHandle($err) {
      echo "<h3>SQL语句出错！</h3>";
      echo "错误状态码：".$err->getCode()."<br>";
      echo "错误行号：".$err->getLine()."<br>";
      echo "错误信息：".$err->getMessage()."<br>";
      echo "错误文件：".$err->getFile()."<br>";
    }
 }
}
