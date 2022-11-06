<?php
use Illuminate\Database\Capsule\Manager;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use App\Config;

$binance = new Manager();
$binance->addConnection(Config::getInstance()->getDataBase());
$binance->setEventDispatcher(new Dispatcher(new Container));
$binance->setAsGlobal();
$binance->bootEloquent();
