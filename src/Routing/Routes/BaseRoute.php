<?php

namespace App\Routing\Routes;

use App\User\BaseUser;
use Twig\Error\Error;

abstract class BaseRoute
{
    protected $path;
    private static $loader;
    private static $twig;

    public function __construct($path)
    {
        $this->path = $path;

        if (self::$loader == null) {
            self::$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../View/');
        }

        if (self::$twig == null) {
            self::$twig = new \Twig\Environment(self::$loader);
        }
    }

    public function preHandle(?BaseUser $user, string $path, string $method): void
    {
        if (hasKeys($this->path)) {
            $this->path[$path]($user, $method);
            return;
        }

        $this->handle($user, $method);
    }

    public abstract function handle(?BaseUser $user, string $method): void;

    public function getPath(): array
    {
        return $this->path;
    }

    public function render(string $template, array $args = [], string $fileExtension = '.twig'): void
    {
        try {
            echo self::$twig->render($template . $fileExtension, $args);
        } catch (Error $e) {
            echo 'Es gab einen Fehler beim Anzeigen der Seite.';
        }
    }
}