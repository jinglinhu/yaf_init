<?php
class Util_Factory {
    
    protected static $objects = [];
    
    public static function getObjectKey($params)
    {
         return empty($params) ? '__key__' : md5(json_encode($params));
    }
    
    public static function create($className, $key = 'default', &$isNew = false)
    {
        if(isset(self::$objects[$className][$key])) return self::$objects[$className][$key];
        
        if(class_exists($className)) {
            $obj = new $className;
            if($obj) self::$objects[$className][$key] = $obj; //能否返回
            $isNew = true;
            return $obj;
        }
        
        return false;
    }
}
