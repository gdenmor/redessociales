<?php

namespace App\Repository;

use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PDO;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<Usuario>
 *
 * @method Usuario|null find($id, $lockMode = null, $lockVersion = null)
 * @method Usuario|null findOneBy(array $criteria, array $orderBy = null)
 * @method Usuario[]    findAll()
 * @method Usuario[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsuarioRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Usuario::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Usuario) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function usuariosescritos($id_user){
        $conexion = new PDO("mysql:host=127.0.0.1:3306;dbname=redsocial;charset=utf8mb4", "root", "Root");
        $resultado = $conexion->prepare("SELECT DISTINCT u2.*
        FROM mensaje m
        INNER JOIN user u1 ON m.emisor_id = u1.id
        INNER JOIN user u2 ON m.receptor_id = u2.id
        where u1.id=:id_user;");
        $resultado->bindParam(":id_user",$id_user,PDO::PARAM_INT);
        $resultado->execute();

        $usuarios = [];

        while ($tuplas = $resultado->fetch(PDO::FETCH_OBJ)) {
            $usuario=new Usuario();
            $usuario->setId($tuplas->id);
            $usuario->setEmail($tuplas->email);
            $usuario->setNombreCompleto($tuplas->nombre_completo);
            $usuario->setUsuario($tuplas->usuario);
            $usuario->setGenero($tuplas->genero);
            $usuario->setFoto($tuplas->foto);
            $usuario->setDescripcion($tuplas->descripcion);
            $usuario->setPassword($tuplas->password);
            $usuario->setRoles((array)$tuplas->roles);
            $usuarios[]=$usuario;
        }

        return $usuarios;
    }

    //    /**
    //     * @return Usuario[] Returns an array of Usuario objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Usuario
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
