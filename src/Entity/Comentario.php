<?php

namespace App\Entity;

use App\Repository\ComentarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Esta es la entidad referente a los comentarios que nos encontraremos en una publicación
 */

#[ORM\Entity(repositoryClass: ComentarioRepository::class)]
class Comentario
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $texto = null;

    #[ORM\ManyToOne(inversedBy: 'comentarios')]
    private ?Usuario $Usuario = null;

    #[ORM\ManyToMany(targetEntity: Interaccion::class, inversedBy: 'comentarios')]
    private Collection $interacciones;

    #[ORM\ManyToOne(inversedBy: 'comentarios')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Publicacion $publicacion = null;

    public function __construct()
    {
        $this->interacciones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTexto(): ?string
    {
        return $this->texto;
    }

    public function setTexto(string $texto): static
    {
        $this->texto = $texto;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->Usuario;
    }

    public function setUsuario(?Usuario $Usuario): static
    {
        $this->Usuario = $Usuario;

        return $this;
    }

    /**
     * @return Collection<int, Interaccion>
     */
    public function getInteracciones(): Collection
    {
        return $this->interacciones;
    }

    public function addInteraccione(Interaccion $interaccione): static
    {
        if (!$this->interacciones->contains($interaccione)) {
            $this->interacciones->add($interaccione);
        }

        return $this;
    }

    public function removeInteraccione(Interaccion $interaccione): static
    {
        $this->interacciones->removeElement($interaccione);

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
}
