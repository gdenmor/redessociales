<?php

namespace App\Repository;

use App\Entity\Comentario;
use App\Entity\Interaccion;
use App\Entity\Publicacion;
use App\Entity\UsuarioInteraccion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @extends ServiceEntityRepository<Interaccion>
 *
 * @method Interaccion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Interaccion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Interaccion[]    findAll()
 * @method Interaccion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InteraccionRepository extends ServiceEntityRepository
{
    private $entityManagerInterface;
    private $security;
    private $usuarioRepository;
    private $usuarioInteraccionRepository;
    public function __construct(ManagerRegistry $registry,EntityManagerInterface $entityManagerInterface,Security $security,UsuarioRepository $usuarioRepository,UsuarioInteraccionRepository $usuarioInteraccionRepository)
    {
        parent::__construct($registry, Interaccion::class);
        $this->entityManagerInterface = $entityManagerInterface;
        $this->security = $security;
        $this->usuarioRepository = $usuarioRepository;
        $this->usuarioInteraccionRepository = $usuarioInteraccionRepository;
    }

    public function addInteraccion(Publicacion $publicacion,Interaccion $interaccion){
        $int=new UsuarioInteraccion();
        $int->setInteraccion($interaccion);
        $int->setPublicacion($publicacion);
        $int->setUsuario($this->usuarioRepository->find($this->security->getUser()));
        $this->entityManagerInterface->persist($int);
        $publicacion->addUsuarioInteraccion($int);
        $this->entityManagerInterface->flush();
    }


    public function addComentario(Publicacion $publicacion,Comentario $comentario){
        
        $publicacion->addComentario($comentario);
        $this->entityManagerInterface->flush();

    }

    public function borraComentario(Publicacion $publicacion,Comentario $comentario){

        $publicacion->removeComentario($comentario);
        $this->entityManagerInterface->flush();

    }

    public function removeInteraccion(Publicacion $publicacion,Interaccion $interaccion){
        $usuario = $this->security->getUser(); // Obtener el usuario actualmente autenticado

        // Buscar la entidad UsuarioInteraccion
       
        $usuarioInteraccion = $this->usuarioInteraccionRepository->findOneBy([
            'usuario' => $usuario,
            'publicacion' => $publicacion,
            'interaccion' => $interaccion,
        ]);

        if ($usuarioInteraccion) {
            // Eliminar la interacción
            $this->entityManagerInterface->remove($usuarioInteraccion);
            $this->entityManagerInterface->flush();
        } else {
            throw new \Exception("La interacción especificada no existe.");
        }

    }

}
