<?php

use Web3\Web3;

class IndexController extends ApiAbstractController
{
    public function indexAction()
    {
        //$user = DeviceModel::getInstance()->find(1);
        //print_r($user->uuid);

        #$b = $web3->getBalance("0x28E762e46419505A6cD8ca428d4eD918e4d3698A");
        #echo $b;

        $web3 = new Web3('http://localhost:8545');

        $b = $web3->eth->getBalance("0x18d4c7A9F9F969bB8e9d96C7F3E77E8A42915e8f", function ($err, $balance) {
            if ($err !== null) {
                echo 'Error: ' . $err->getMessage();
                return;
            }
            echo 'Balance: ' . $balance->toString() . PHP_EOL;
        });

        // $web3->clientVersion(function ($err, $version) {
        //     if ($err !== null) {
        //         // do something
        //         return;
        //     }
        //     if (isset($version)) {
        //         echo 'Client version: ' . $version;
        //     }
        // });

    }

}
