<?php

namespace App\Routing;

use App\Container\Container;
use App\Service\SecurityService;
use App\User\BaseUser;

class Router
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function processRequest(): void
    {
        $routes = $this->container->getRoutes();
        $realPath = explode('?', $_SERVER['REQUEST_URI'])[0];

        if (substr($realPath, -1) == '/') $realPath = substr($realPath, 0, -1);

        foreach ($routes as $route) {
            $paths = $route->getPath();
            if (!is_array($paths)) $paths = array($paths);

            if (hasKeys($paths)) {
                foreach ($paths as $path => $callback) {
                    if ($path == $realPath) {
                        $route->preHandle($this->container->get(SecurityService::class)->getUser(), $path, $_SERVER['REQUEST_METHOD']);
                        return;
                    }
                }
            }

            foreach ($paths as $path) {
                if ($path == $realPath) {
                    $route->preHandle($this->container->get(SecurityService::class)->getUser(), $path, $_SERVER['REQUEST_METHOD']);
                    return;
                }
            }
        }

        //header('Location: /dashboard');
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}