<?php

namespace App\Container;

use App\Routing\Router;
use App\Routing\Routes\AdminRoute;
use App\Routing\Routes\BaseRoute;
use App\Routing\Routes\DashboardRoute;
use App\Routing\Routes\LoginRoute;
use App\Routing\Routes\LogoutRoute;
use Exception;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;

class Container implements ContainerInterface
{
    protected $instances = array();

    public function __construct()
    {
        array_push($this->instances, $this);
        $this->bootstrap();
    }

    private function bootstrap() {
        try {
            $this->get(Router::class);
            $this->get(AdminRoute::class);
            $this->get(DashboardRoute::class);
            $this->get(LoginRoute::class);
            $this->get(LogoutRoute::class);
        } catch (NotFoundException $e) {
            echo $e;
        }
    }

    public function get(string $class)
    {
        if (!$this->has($class)) {
            try {
                return $this->instantiate($class);
            } catch (NotFoundException $e) {
                throw $e;
            }
        }

        foreach ($this->instances as $instance) {
            if($class == get_class($instance)) {
                return $instance;
            }
        }

        throw new NotFoundException();
    }

    public function has(string $class): bool
    {
        foreach ($this->instances as $instance) {
            if($class == get_class($instance)) {
                return true;
            }
        }
        return false;
    }

    public function getRoutes(): array {
        $routes = array();

        foreach ($this->instances as $instance) {
            if(is_subclass_of($instance, BaseRoute::class)) array_push($routes, $instance);
        }

        return $routes;
    }

    public function add($instance) {
        array_push($this->instances, $instance);
    }

    private function instantiate($class) {
        try {
            $reflector = new ReflectionClass($class);
            $constructorArguments = $reflector->getConstructor()->getParameters();

            if(sizeof($constructorArguments) == 0) {
                $instance = $reflector->newInstance();
                array_push($this->instances, $instance);
                return $instance;
            }

            $args = array();

            foreach ($constructorArguments as $argumentIndex => $constructorArgument) {
                $argumentClassHint = $constructorArgument->getClass();

                if($this->has($argumentClassHint->getName())) {
                    array_push($args, $this->get($argumentClassHint->getName()));
                } else {
                    try {
                        array_push($args, $this->instantiate($argumentClassHint->getName()));
                    } catch (Exception $e) {
                        throw new Exception();
                    }
                }
            }
            $instance = $reflector->newInstanceArgs($args);
            array_push($this->instances, $instance);
            return $instance;
        } catch (ReflectionException $e) {
            throw $e;
        }
    }
}