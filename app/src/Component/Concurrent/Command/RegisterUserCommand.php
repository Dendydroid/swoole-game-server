<?php

namespace App\Component\Concurrent\Command;

use App\Database\Entity\User;
use App\Database\Traits\DatabaseAccess;
use App\Tcp\Auth\AuthStrategy;
use JetBrains\PhpStorm\ArrayShape;
use Throwable;

class RegisterUserCommand extends BaseCommand
{
    use DatabaseAccess;

    public function getSignature(): string
    {
        return "register";
    }

    #[ArrayShape(["text" => "string"])] public function handle(): array
    {
        $email = $this->get("e");
        $pass = $this->get("p");

        if ($email && $pass) {
            $user = $this->em->getRepository(User::class)->findOneBy(["email" => $email]);
            if ($user) {
                return $this->message("User with email `$email` already exists!");
            }
            try {
                $user = (new User())->setEmail($email)
                    ->setPassword(AuthStrategy::hashPassword($pass));

                $this->em->persist($user);
                $this->em->flush();

                return $this->message("New user has been successfully created (User#{$user->getId()})");

            } catch (Throwable $exception) {
                return $this->message("Error on creating a user: " . $exception->getMessage());
            }
        } else {
            return $this->message("You have to specify both `e` and `p` parameters (email and password)");
        }
    }

}