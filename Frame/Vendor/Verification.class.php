<?php
/**
 * 这是一个图像验证码类
 */

namespace Frame\Vendor {
  final class Verification
  {
    private $code; // 验证码字符串
    private $codelen; // 验证码长度
    private $width; // 图像宽度
    private $height; // 图像高度
    private $img; // 图像资源画布
    private $fontsize; // 验证码字体大小
    private $fontfile; // 字体文件

    // 构造方法初始化
    public function __construct($codelen = 4, $width = 84, $height = 40, $fontsize = 24)
    {
      $this->codelen = $codelen;
      $this->width = $width;
      $this->height = $height;
      $this->fontsize = $fontsize;
      $this->fontfile = ADMIN_FONT_PATH."simhei.ttf";
      $this->img = $this->createImg();
      $this->code = $this->createCode();
      $this->createBg(); // 画布资源背景填充
      $this->createText(); // 写入字符串
      $this->output(); // 输出图片资源
    }

    // 创建随机字符串
    private function createCode()
    {
      // 产生字符串数组
      $arr_str = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'));
      // 打乱数组
      shuffle($arr_str);
      shuffle($arr_str);
      // 随机从数组红取出指定个数的单元的下标
      $arr_index = array_rand($arr_str, $this->codelen);
      // 循环拿到随机字符串
      $srt = '';
      foreach ($arr_index as $i) {
        $srt .= $arr_str[$i];
      }
      // 把随机结果写入session
      $_SESSION['code'] = $srt;
      return $srt;
    }

    // 创建画布
    private function createImg()
    {
      return imagecreatetruecolor($this->width, $this->height);
    }

    // 背景色填充
    private function createBg()
    {
      // 创建颜色
      $color = imagecolorallocate($this->img, mt_rand(0, 200), mt_rand(0, 200), mt_rand(0, 200));
      // 绘制带背景色的矩形
      imagefilledrectangle($this->img, 0, 0, $this->width, $this->height, $color);
    }

    // 在图像资源中写入字符串
    private function createText()
    {
      for ($i=0;$i<$this->codelen;$i++) {
        // 创建文字颜色
        $color = imagecolorallocate($this->img, mt_rand(100, 255), mt_rand(100, 255), mt_rand(100, 255));
        // 写入文本
        imagettftext($this->img, $this->fontsize, mt_rand(-20,20), 16*($i+1), 30, $color, $this->fontfile, $this->code[$i]);
      }
    }

    // 输出图像资源
    private function output()
    {
      // 声明输出的内容是MIME类型
      header('content-type:image/png');
      // 输出图像
      imagepng($this->img);
      // 输出后销毁资源，节省内存
      imagedestroy($this->img);
    }
  }
}

