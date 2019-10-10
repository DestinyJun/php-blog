<?php
namespace Frame\Libs{
  final class Db
  {
    // 一私：私有的静态的保存对象的属性；
    private static $obj  = null;
    // 私有的数据库配置信息
    private $db_connect; // 数据库链接
    private $db_host; // 主机名
    private $db_user; // 用户名
    private $db_pass; // 数据库密码
    private $db_name; // 数据库名
    private $charset; // 字符集
    // 一私：私有的构造方法，阻止类外new对象；限定参数,只能传数组
    private function __construct() // 一私
    {
      // 初始化数据配置信息
      $this->db_host = $GLOBALS['config']['db_host'];
      $this->db_user = $GLOBALS['config']['db_user'];
      $this->db_pass = $GLOBALS['config']['db_pass'];
      $this->db_name = $GLOBALS['config']['db_name'];
      $this->charset = $GLOBALS['config']['db_charset'];
      // 连接数据库
      $this->connectDb();
      // 选择数据库
      $this->selectDb();
      // 设置数据库字符集
      $this->setCharset();
    }
    // 在创建方法的时候，还要想一想此方法是否需要在类外调用，如果不需要，就要统统写为私有方法，成员属性也是一样的，静态
    //属性、静态方法等也是一样的
    // 一私：私有的克隆方法，阻止类外clone对象；
    private function __clone() // 一私
    {
      // TODO: Implement __clone() method.
    }
    // 一公：共有的静态的创建对象的方法。（
    public static function getInstance()
    {
      // 判断当前对象是否存在
      if (!self::$obj instanceof self) {
        // 如果对象不存在，就创建他
        self::$obj = new self();
      }
      // 返回当前对象
      return self::$obj;
    }
    // 私有函数连接数据库
    private function connectDb()
    {
      if (!@($this->db_connect = mysqli_connect($this->db_host,$this->db_user,$this->db_pass)))
        die("PHP连接数据库失败");
    }
    // 私有函数选择数据库
    private function selectDb()
    {
      if (!(mysqli_select_db($this->db_connect,$this->db_name)))
        die("选择数据库{$this->db_name}失败！");
    }
    // 私有的设置数据库字符集
    private function setCharset()
    {
      //    mysql_query("set names {$this->charset}");
      // 这里改为一个方法来执行
      $this->exec("set names {$this->charset}");
    }
    // 只执行返回结果集的select查询语句，因为直接处理好了返回结果，所以不能在类外调用
    private function query($sql) {
      // 将SQL语句转换成大小写，统一一下
      $sql = strtolower($sql);
      // 因为SQL语句分为返回结果集的语句以及返回真假的语句，所以这里要做下处理
      // 判断SQL语句是不是select语句
      if ((!substr($sql,0,6)  === 'select')) {
        // 如果为真，不执行SQL语句并作出提示
        die("该方法不能执行非select的SQL语句");
      }
      // 如果是select的语句，则正常执行，并返回结果集（布尔值）
      return mysqli_query($this->db_connect,$sql);
    }
    // 只执行返回真假的SQ语句的函数 （如增删改数据库语句）
    public function exec($sql){
      // 将SQL语句转换成大小写，统一一下
      $sql = strtolower($sql);
      // 因为SQL语句分为返回结果集的语句以及返回真假的语句，所以这里要做下处理
      // 判断SQL语句是不是select语句
      if (substr($sql,0,6)  === 'select') {
        // 如果为真，不执行SQL语句并作出提示
        die("该方法不能执行select的SQL语句");
      }
      // 如果是非select的语句，则正常执行，并返回结果（布尔值）
      return mysqli_query($this->db_connect,$sql);
    }
    // 公共的获取单行记录的方法
    public function fetchOne($sql,$type = 1) {
      // 执行sql语句，并返回结果
      $result = mysqli_query($this->db_connect,$sql);
      // 这里定义$type来控制用户是否是要关联数组数据还是索引数组数据还是全都要
      // MYSQLI_NUM 枚举数组 MYSQLI_BOTH索引 MYSQLI_ASSOC单条记录
      $types = array(1 => MYSQLI_ASSOC,2 => MYSQLI_NUM,3 => MYSQLI_BOTH);
      // 返回一条记录
      return mysqli_fetch_array($result,$types[$type]);
    }
    // 公共的获取多行记录的方法
    public function fetchAll($sql,$type = 1) {
      // 执行sql语句，并返回结果
      $result = mysqli_query($this->db_connect,$sql);
      // 这里定义$type来控制用户是否是要关联数组数据还是索引数组数据还是全都要
      // MYSQLI_ASSOC=关联数组  MYSQLI_NUM=索引素组  MYSQLI_BOTH=两者都有
      $types = array(1 => MYSQLI_ASSOC,2 => MYSQLI_NUM,3 => MYSQLI_BOTH);
      // 返回一个数组，而不是结果集，结果集直接就在这里处理了
      while ($row = mysqli_fetch_array($result,$type)) {
        $arr[] = $row;
      }
      return $arr;
    }
    // 公共的获取记录数的方法
    public function rowCount($sql) {
      // 执行sql语句并返回结果集
      $result = $this->query($sql);
      // 从结果集中拿到记录数并返回
      return mysqli_num_rows($result);
    }
  }
}
