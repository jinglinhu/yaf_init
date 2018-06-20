<?php

/**
 * Class AbstractController
 */
abstract class AbstractController extends Yaf\Controller_Abstract
{
    /**
     * 初始化
     */
    public function init()
    {
        header("Content-Type:text/html;charset=utf-8");
    }

}
