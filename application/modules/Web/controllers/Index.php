<?php
class IndexController extends WebAbstractController
{
    public function indexAction()
    {

        $this->getView()->display('index/index.html');
    }

}
