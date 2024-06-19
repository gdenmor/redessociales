<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A continuación, vamos a tener la entidad que es la referente
 * a tosos los usuarios registrados en la aplicación
 */

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
#[ORM\Table(name: 'user')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class Usuario implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function setId($id){
        $this->id = $id;
    }

    #[ORM\Column(length: 180)]
    #[Assert\Email(message: 'Email no válido')]
    #[Assert\NotBlank(message: 'Email no válido')]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\Length(min:6,minMessage:'Contraseña no válida')]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'El usuario no puede estar vacío')]
    private ?string $usuario = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'El nombre completo no puede estar vacío')]
    private ?string $nombre_completo = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'Debe de seleccionar un género')]
    private ?string $genero = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $descripcion = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $foto = null;

    #[ORM\OneToMany(targetEntity: Comentario::class, mappedBy: 'Usuario',orphanRemoval: true)]
    private Collection $comentarios;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'seguidores',orphanRemoval: true)]
    #[ORM\JoinTable(name: 'user_seguidores')]
    private Collection $seguidores;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'seguidos',orphanRemoval: true)]
    #[ORM\JoinTable(name: 'user_seguidos')]
    private Collection $seguidos;

    #[ORM\OneToMany(targetEntity: Mensaje::class, mappedBy: 'emisor', orphanRemoval: true)]
    private Collection $mensajes;


    #[ORM\OneToMany(targetEntity: Publicacion::class, mappedBy: 'Usuario')]
    private Collection $publicaciones;

    #[ORM\Column]
    private ?bool $baneado = null;

    public function __construct()
    {
        $this->comentarios = new ArrayCollection();
        $this->seguidores = new ArrayCollection();
        $this->seguidos = new ArrayCollection();
        $this->mensajes = new ArrayCollection();
        $this->publicaciones = new ArrayCollection();
        $this->baneado = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsuario(): ?string
    {
        return $this->usuario;
    }

    public function setUsuario(string $usuario): static
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getNombreCompleto(): ?string
    {
        return $this->nombre_completo;
    }

    public function setNombreCompleto(string $nombre_completo): static
    {
        $this->nombre_completo = $nombre_completo;

        return $this;
    }

    public function getGenero(): ?string
    {
        return $this->genero;
    }

    public function setGenero(string $genero): static
    {
        $this->genero = $genero;

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

    public function getFoto(): ?string
    {
        return $this->foto;
    }

    public function setFoto(?string $foto): static
    {
        $this->foto = $foto;

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
        if (!$this->comentarios->contains($comentario)) {
            $this->comentarios->add($comentario);
            $comentario->setUsuario($this);
        }

        return $this;
    }

    public function removeComentario(Comentario $comentario): static
    {
        if ($this->comentarios->removeElement($comentario)) {
            // set the owning side to null (unless already changed)
            if ($comentario->getUsuario() === $this) {
                $comentario->setUsuario(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getSeguidores(): Collection
    {
        return $this->seguidores;
    }

    public function addSeguidore(self $seguidore): static
    {
        if (!$this->seguidores->contains($seguidore)) {
            $this->seguidores->add($seguidore);
        }

        return $this;
    }

    public function removeSeguidore(self $seguidore): static
    {
        $this->seguidores->removeElement($seguidore);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getSeguidos(): Collection
    {
        return $this->seguidos;
    }

    public function addSeguido(self $seguido): static
    {
        if (!$this->seguidos->contains($seguido)) {
            $this->seguidos->add($seguido);
        }

        return $this;
    }

    public function removeSeguido(self $seguido): static
    {
        $this->seguidos->removeElement($seguido);

        return $this;
    }

    /**
     * @return Collection<int, Mensaje>
     */
    public function getMensajes(): Collection
    {
        return $this->mensajes;
    }

    public function addMensaje(Mensaje $mensaje): static
    {
        if (!$this->mensajes->contains($mensaje)) {
            $this->mensajes->add($mensaje);
            $mensaje->setEmisor($this);
        }

        return $this;
    }

    public function removeMensaje(Mensaje $mensaje): static
    {
        if ($this->mensajes->removeElement($mensaje)) {
            // set the owning side to null (unless already changed)
            if ($mensaje->getEmisor() === $this) {
                $mensaje->setEmisor(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection<int, Publicacion>
     */
    public function getPublicaciones(): Collection
    {
        return $this->publicaciones;
    }

    public function addPublicacione(Publicacion $publicacione): static
    {
        if (!$this->publicaciones->contains($publicacione)) {
            $this->publicaciones->add($publicacione);
            $publicacione->setUsuario($this);
        }

        return $this;
    }

    public function removePublicacione(Publicacion $publicacione): static
    {
        if ($this->publicaciones->removeElement($publicacione)) {
            // set the owning side to null (unless already changed)
            if ($publicacione->getUsuario() === $this) {
                $publicacione->setUsuario(null);
            }
        }

        return $this;
    }

    public function isBaneado(): ?bool
    {
        return $this->baneado;
    }

    public function setBaneado(bool $baneado): static
    {
        $this->baneado = $baneado;

        return $this;
    }

}
