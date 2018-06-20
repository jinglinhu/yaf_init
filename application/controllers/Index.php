<?php
class IndexController extends AbstractController
{
    // 默认Action
    public function indexAction()
    {
        $user = DeviceModel::getInstance()->find(1);
        print_r($user->uuid);exit;
        $this->getView()->display('layout/index.html');
    }

}
