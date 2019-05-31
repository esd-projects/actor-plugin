<?php

use ESD\Plugins\Actor\ActorPlugin;
use ESD\Plugins\Actor\ExampleClass\TestPort;
use ESD\Server\Co\ExampleClass\DefaultServer;

require __DIR__ . '/../vendor/autoload.php';

define("ROOT_DIR", __DIR__ . "/..");
define("RES_DIR", __DIR__ . "/resources");
$server = new DefaultServer(null,TestPort::class);
$server->getPlugManager()->addPlug(new ActorPlugin());
//配置
$server->configure();
//启动
$server->start();
