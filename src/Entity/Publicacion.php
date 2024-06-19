<?php

namespace App\Entity;

use App\Repository\PublicacionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**A continuación esta es la entidad referente a las publicaciones que nos
    podemos encontrar en la aplicación
**/
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
    #[Assert\NotBlank(message:'Debe de existir algun archivo')]
    private array $archivos = [];


    #[ORM\ManyToOne(inversedBy: 'publicaciones')]
    #[Assert\NotBlank(message:'Debe de existir algun usuario que cree la aplicación')]
    private ?Usuario $Usuario = null;
    #[ORM\OneToMany(targetEntity: UsuarioInteraccion::class, mappedBy: 'publicacion')]
    private Collection $usuarioInteracciones;

    #[ORM\OneToMany(targetEntity: Comentario::class, mappedBy: 'publicacion')]
    private Collection $comentarios;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $descripcion = null;

    #[ORM\ManyToOne(inversedBy: 'publicaciones')]
    #[Assert\NotBlank(message:'Debe de existir algun tipo de publicación')]
    private ?TiposPublicacion $tipo = null;

    public function __construct()
    {
        $this->comentarios = new ArrayCollection();
        $this->usuarioInteracciones = new ArrayCollection();
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

    /**
     * @return Collection<int, UsuarioInteraccion>
     */
    public function getusuarioInteracciones(): Collection
    {
        return $this->usuarioInteracciones;
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
    public function addUsuarioInteraccion(UsuarioInteraccion $usuarioInteraccion): static
    {
        if (!$this->usuarioInteracciones->contains($usuarioInteraccion)) {
            $this->usuarioInteracciones->add($usuarioInteraccion);
            $usuarioInteraccion->setPublicacion($this);
        }
        return $this;
    }

    public function removeUsuarioInteraccion(UsuarioInteraccion $usuarioInteraccion): static
    {
        if ($this->usuarioInteracciones->removeElement($usuarioInteraccion)) {
            if ($usuarioInteraccion->getPublicacion() === $this) {
                $usuarioInteraccion->setPublicacion(null);
            }
        }
        return $this;
    }
}
