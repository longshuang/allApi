<?php
/**
 * 工厂单例生产类
 * User: long
 * Date: 2019/7/25
 * Time: 14:57
 */

namespace App;

trait FactoryInstanceTrait
{
    public static $instance = [];

    public static function getInstance($className, $parameters = null)
    {
        if (empty(self::$instance[$className])) {
            self::$instance[$className] = new $className($parameters);
        }
        return self::$instance[$className];
    }
}