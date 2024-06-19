<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Esta entidad relaciona a los usuarios con las interacciones en las publicaciones.
 */
#[ORM\Entity]
class UsuarioInteraccion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    private ?Usuario $usuario = null;

    #[ORM\ManyToOne(targetEntity: Publicacion::class, inversedBy: 'usuarioInteracciones')]
    private ?Publicacion $publicacion = null;

    #[ORM\ManyToOne(targetEntity: Interaccion::class)]
    private ?Interaccion $interaccion = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): static
    {
        $this->usuario = $usuario;
        return $this;
    }

    public function getPublicacion(): ?Publicacion
    {
        return $this->publicacion;
    }

    public function setPublicacion(?Publicacion $publicacion): static
    {
        $this->publicacion = $publicacion;
        return $this;
    }

    public function getInteraccion(): ?Interaccion
    {
        return $this->interaccion;
    }

    public function setInteraccion(?Interaccion $interaccion): static
    {
        $this->interaccion = $interaccion;
        return $this;
    }
}

