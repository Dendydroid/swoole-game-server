<?php

namespace App\Tcp\Controller;

use App\Component\Application\GameApplication;
use App\Database\Entity\User;
use App\Tcp\Auth\AuthService;
use App\Tcp\Auth\AuthStrategy;

class AuthController extends BaseController
{
    public function authenticate(array $data): void
    {
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        $em = GameApplication::database()->getEntityManger();
        /** @var User|null $user */
        $user = $em->getRepository(User::class)->findOneBy(["email" => $email]);

        if ($user && AuthStrategy::validPassword($password, $user->getAuthPassword())) {
            $auth = new AuthService();
            $token = $auth->generateToken($user->getId());
            $this->connection->setUserId($user->getId());
            GameApplication::updateConnections();
            $this->response(["token" => $token]);
        }
    }
}
