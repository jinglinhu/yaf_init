<?php
class Util_Conf {
    private static $files = [];

    private static $delimiter = '.';

    public static function get($key) {
        if (strpos($key, '.') !== false) {
            list($file, $path) = explode('.', $key, 2);
        } else {
            $file = $key;
        }

        $config_file = APP_PATH . '/conf/' . $file . '.ini';
        
        if (!file_exists($config_file)) {
            return false;
        }

        $env = ini_get('yaf.environ');

        if (!isset(self::$files[$file][$env])) {
            self::$files[$file][$env] = new \Yaf\Config\Ini($config_file, $env);
        }
        $fileArray = self::$files[$file][$env]->toArray();

        if (!isset($path)) {
            return $fileArray;
        } else {
            if (isset($fileArray[$path])) {
                return $fileArray[$path];
            }
            $keys = explode(self::$delimiter, $path);
            $array = $fileArray;
            while ($keys) {
                $key = array_shift($keys);
                if (isset($array[$key])) {
                    if ($keys) {
                        if (!is_array($array[$key])) {
                            return NULL;
                        } else {
                            $array = $array[$key];
                        }
                    } else {
                        return $array[$key];
                    }
                }
            }
        }
    }
}
