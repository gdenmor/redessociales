<?php

namespace App\Entity;

use App\Repository\PublicacionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PublicacionRepository::class)]
class Publicacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $archivos = [];


    #[ORM\ManyToOne(inversedBy: 'publicaciones')]
    private ?Usuario $Usuario = null;

    #[ORM\OneToMany(targetEntity: Comentario::class, mappedBy: 'publicacion')]
    private Collection $comentarios;

    /**
     * @var Collection<int, Interaccion>
     */
    #[ORM\ManyToMany(targetEntity: Interaccion::class)]
    private Collection $interacciones;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $descripcion = null;

    #[ORM\ManyToOne(inversedBy: 'publicaciones')]
    private ?TiposPublicacion $tipo = null;

    public function __construct()
    {
        $this->comentarios = new ArrayCollection();
        $this->interacciones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getArchivos(): array
    {
        return $this->archivos;
    }

    public function setArchivos(array $archivos): static
    {
        $this->archivos = $archivos;

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
     * @return Collection<int, Comentario>
     */
    public function getComentarios(): Collection
    {
        return $this->comentarios;
    }

    public function addComentario(Comentario $comentario): static
    {
        $this->comentarios->add($comentario);
        $comentario->setPublicacion($this);

        return $this;
    }

    public function removeComentario(Comentario $comentario): static
    {
        if ($this->comentarios->removeElement($comentario)) {
            // set the owning side to null (unless already changed)
            if ($comentario->getPublicacion() === $this) {
                $comentario->setPublicacion(null);
            }
        }

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

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getTipo(): ?TiposPublicacion
    {
        return $this->tipo;
    }

    public function setTipo(?TiposPublicacion $tipo): static
    {
        $this->tipo = $tipo;

        return $this;
    }
}
