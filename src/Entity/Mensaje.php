<?php

namespace App\Entity;

use App\Repository\MensajeRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * En esta entidad se instanciarán objetos cuando se mande un mensaje a cualquier otro usuario
 */

#[ORM\Entity(repositoryClass: MensajeRepository::class)]
class Mensaje
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function setId($id){
        $this->id = $id;
    }

    #[ORM\Column(length: 255)]
    private ?string $mensaje = null;

    #[ORM\Column(type: 'datetime')]
    private ?DateTime $fecha_mensaje = null;

    #[ORM\ManyToMany(targetEntity: Interaccion::class, inversedBy: 'mensajes')]
    private Collection $interacciones;

    #[ORM\ManyToOne(inversedBy: 'mensajes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $emisor = null;

    #[ORM\ManyToOne(inversedBy: 'mensajes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $receptor = null;

    #[ORM\Column]
    private ?bool $reportado = null;

    public function __construct()
    {
        $this->interacciones = new ArrayCollection();
        $this->reportado = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMensaje(): ?string
    {
        return $this->mensaje;
    }

    public function setMensaje(string $mensaje): static
    {
        $this->mensaje = $mensaje;

        return $this;
    }

    public function getFechaMensaje(): ?DateTime
    {
        return $this->fecha_mensaje;
    }

    public function setFechaMensaje(DateTime $fecha_mensaje): static
    {
        $this->fecha_mensaje = $fecha_mensaje;

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

    public function getEmisor(): ?Usuario
    {
        return $this->emisor;
    }

    public function setEmisor(?Usuario $emisor): static
    {
        $this->emisor = $emisor;

        return $this;
    }

    public function getReceptor(): ?Usuario
    {
        return $this->receptor;
    }

    public function setReceptor(?Usuario $receptor): static
    {
        $this->receptor = $receptor;

        return $this;
    }

    public function isReportado(): ?bool
    {
        return $this->reportado;
    }

    public function setReportado(bool $reportado): static
    {
        $this->reportado = $reportado;

        return $this;
    }
}
