<?php
class IndexController extends ApiAbstractController
{
    public function indexAction()
    {
        $user = DeviceModel::getInstance()->find(1);
        print_r($user->uuid);
    }

}
