<?php

class Util_Agent
{
    public static $isIOS        = null;
    public static $isAndroid    = null;
    public static $agentVersion = null;

    public static function matchUserAgent($pattern, $user_agent = null)
    {
        if (is_null($user_agent)) {
            $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
        }
        if ($user_agent && preg_match($pattern, $user_agent)) {
            return true;
        }
        return false;
    }

    public static function isIOS($user_agent = null)
    {
        if (self::$isIOS !== null) {
            return self::$isIOS;
        }

        return static::matchUserAgent('/(iPhone|iPad|iPod|iOS).+$/', $user_agent);
    }

    public static function isAndroid($user_agent = null)
    {
        if (self::$isAndroid !== null) {
            return self::$isAndroid;
        }

        return static::matchUserAgent('/Android.+$/', $user_agent);
    }

    public static function getAgentVersion($user_agent = null)
    {
        if (self::$agentVersion !== null) {
            return self::$agentVersion;
        }

        $version = 0;
        if (is_null($user_agent)) {
            $user_agent = array_get($_SERVER, 'HTTP_USER_AGENT');
        }

        if (!$user_agent) {
            return $version;
        }

        if (preg_match('/Wallet\/([0-9.]*).+$/', $user_agent, $matches)) {
            $version = $matches[1];
            $version = intval(preg_replace('/\./', '', $version));
        }
        return $version;

    }
}
