<?php
namespace App;

use App\Container\Container;
use App\Routing\Router;
use App\Util\Database;

class App {
    private $container;

    public function __construct() {
        $this->container = new Container();
        $this->container->get(Router::class)->processRequest();
    }

    public function __destruct()
    {
        if($this->container->has(Database::class)) $this->container->get(Database::class)->getConnection()->close();
    }
}

