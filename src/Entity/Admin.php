<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Admin extends Utilisateur
{
    // Admin hérite de toutes les propriétés de Utilisateur
    // Le rôle ROLE_ADMIN doit être défini lors de la création dans les fixtures
}
