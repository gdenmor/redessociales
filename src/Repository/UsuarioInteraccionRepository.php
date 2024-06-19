<?php
// src/Repository/UsuarioInteraccionRepository.php

namespace App\Repository;

use App\Entity\UsuarioInteraccion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UsuarioInteraccionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsuarioInteraccion::class);
    }

    // Aquí puedes definir tus métodos personalizados si los necesitas
}
