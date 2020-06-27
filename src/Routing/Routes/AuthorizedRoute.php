<?php

namespace App\Routing\Routes;

use App\User\BaseUser;

abstract class AuthorizedRoute extends BaseRoute
{
    private $accessLevel;

    public function __construct(array $path, int $accessLevel)
    {
        parent::__construct($path);
        $this->accessLevel = $accessLevel;
    }

    public function preHandle(?BaseUser $user, string $path, string $method): void
    {
        if ($user == null) {
            http_response_code(403);
            header('Location: /login?ref=' . $path);
            return;
        }

        if($user->getAccessLevel() < $this->accessLevel) {
            echo "403";
            return;
        }

        if (hasKeys($this->path)) {
            $this->path[$path]($user, $method);
            return;
        }

        $this->handle($user, $method);
    }

    public function getAccessLevel(): int {
        return $this->accessLevel;
    }
}