<?php

/**
 * 这是一个工厂工具类
 */
final class Factory
{
  private static $obj = array();
  public static function getInstance($className) {
    if (!isset(self::$obj[$className])) {
      self::$obj[$className] = new $className();
    }
    return self::$obj[$className];
  }
}
