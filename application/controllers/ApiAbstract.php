<?php

abstract class ApiAbstractController extends Yaf\Controller_Abstract
{
    /**
     * 初始化
     */
    public function init()
    {
        Yaf\Dispatcher::getInstance()->disableView();
        header("Content-Type:text/json;charset=utf-8");
    }

    /**
     * 标准化信息返回
     * @param $code
     * @param string $data
     * @return array
     */
    protected function returnResult($data = '', $code = 200, $msg = '')
    {
        $code = intval($code);

        //若有错误信息则覆盖错误代码
        if (!$msg) {
            $msg = Define_ErrorCode::getErrMsg($code);
        }
        $result = [
            'code' => $code,
            'msg'  => $msg,
            'data' => $data,
        ];

        echo json_encode($result);
        exit;
    }
}
