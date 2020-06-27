<?php

namespace App\Routing\Routes;

use App\App;
use App\Container\Container;
use App\Service\SecurityService;
use App\Service\UserService;
use App\User\AdminUser;
use App\User\BaseUser;
use App\User\DefaultUser;
use App\Util\Database;

class AdminRoute extends AuthorizedRoute
{
    private $userService;
    private $securityService;
    private $connection;

    public function __construct(UserService $userService, SecurityService $securityService, Database $database)
    {
        $this->userService = $userService;
        $this->securityService = $securityService;
        $this->connection = $database->getConnection();

        parent::__construct(array(
            '/admin' => array($this, 'handle'),
            '/admin/add-user' => array($this, 'addUser'),
            '/admin/delete-user' => array($this, 'deleteUser'),
        ), AdminUser::ACCESS_LEVEL);
    }

    public function handle(?BaseUser $user, string $method): void
    {
        $this->render('AdminDashboardView');
    }

    public function addUser(?BaseUser $user, string $method): void
    {
        $args = [];

        if (isset($_POST['vorname']) && isset($_POST['nachname']) && isset($_POST['email']) && isset($_POST['password'])) {
            if ($this->securityService->isEmailInDatabase($_POST['email'])) {
                $args['status'] = 'E-Mail wird bereits verwendet';
            } else {
                // TODO ID Update
                $this->userService->addUser(new DefaultUser(-1, $_POST['email'], $_POST['vorname'], $_POST['nachname']), $_POST['password']);
                $args['status'] = 'User hinzugefügt';
            }
        }

        $this->render('AddUserView', $args);
    }

    public function deleteUser(?BaseUser $user, string $method): void
    {
        $status = '';

        if (isset($_POST['id'])) {
            $this->userService->deleteUser($_POST['id']);
            $status = 'User gelöscht';

            if ($_POST['id'] == $user->getId()) {
                $this->securityService->logout();
                header('Location: /login');
            }
        }

        $statement = $this->connection->prepare('SELECT id, email FROM users');
        $statement->execute();
        $result = $statement->get_result();

        $users = array();

        while ($row = $result->fetch_assoc()) {
            array_push($users, ['id' => $row['id'], 'email' => $row['email']]);
        }

        $this->render('DeleteUserView', ['users' => $users, 'status' => $status]);
    }
}