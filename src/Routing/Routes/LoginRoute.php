<?php

namespace App\Routing\Routes;

use App\Service\SecurityService;
use App\User\BaseUser;

class LoginRoute extends BaseRoute
{
    private $securityService;

    public function __construct(SecurityService $securityService)
    {
        $this->securityService = $securityService;
        parent::__construct(['/login']);
    }

    public function handle(?BaseUser $user, string $method): void
    {
        if ($user) {
            header('Location: /dashboard');
            return;
        }

        $args = [];

        if (isset($_POST['email']) && isset($_POST['password'])) {
            if ($this->securityService->login($_POST['email'], $_POST['password'])) {
                if(isset($_GET['ref'])) {
                    header('Location: ' . $_GET['ref']);
                } else {
                    header('Location: /dashboard');
                }
            } else {
                $args['error'] = 'Daten inkorrekt';
            }
        }

        $this->render('LoginView', $args);
    }
}