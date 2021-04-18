<?php

namespace App\Database\Traits;

use App\Component\Application\GameApplication;
use Doctrine\ORM\EntityManagerInterface;

trait DatabaseAccess
{
    protected EntityManagerInterface $em;

    public function __construct()
    {
        $this->em = GameApplication::database()->getEntityManger();
    }

}