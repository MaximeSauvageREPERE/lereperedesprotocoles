<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Admin extends Utilisateur
{
    public function __construct()
    {
        parent::__construct();
        // Un admin a automatiquement le rôle ROLE_ADMIN
        $this->setRoles(['ROLE_ADMIN']);
    }
}
