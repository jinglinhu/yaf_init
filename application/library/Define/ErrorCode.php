<?php
class Define_ErrorCode
{
    //成功
    const CODE_SUCCESS = 200;

    const CODE_ERR_PARAM  = 400;
    const CODE_ERR_SERVER = 500;

    public static $messageMap = [
        self::CODE_SUCCESS    => 'success',

        self::CODE_ERR_PARAM  => 'params error',
        self::CODE_ERR_SERVER => 'server error',
    ];

    public static $defaultError = "error message not set!";

    /**
     * @param $code
     * @return mixed|string
     */
    public static function getErrMsg($code)
    {
        if (isset(self::$messageMap[$code])) {
            return self::$messageMap[$code];
        }
        return self::$defaultError;
    }
}
