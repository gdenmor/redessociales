<?php

namespace App\Repository;

use App\Entity\Mensaje;
use DateTime;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PDO;

/**
 * @extends ServiceEntityRepository<Mensaje>
 *
 * @method Mensaje|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mensaje|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mensaje[]    findAll()
 * @method Mensaje[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MensajeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mensaje::class);
    }

    //    /**
    //     * @return Mensaje[] Returns an array of Mensaje objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Mensaje
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function mensajes($id_emisor,$id_receptor,UsuarioRepository $usuarioRepository){
        $conexion = new PDO("mysql:host=127.0.0.1:3306;dbname=redsocial;charset=utf8mb4", "root", "Root");
        $resultado = $conexion->prepare("select * from mensaje where emisor_id=:id_emisor and receptor_id=
        :id_receptor or emisor_id=:id_receptor and receptor_id=:id_emisor order by fecha_mensaje;");
        $resultado->bindParam(":id_emisor",$id_emisor,PDO::PARAM_INT);
        $resultado->bindParam(":id_receptor",$id_receptor,PDO::PARAM_INT);
        $resultado->execute();

        $mensajes = [];

        while ($tuplas = $resultado->fetch(PDO::FETCH_OBJ)) {
            $mensaje=new Mensaje();
            $mensaje->setId($tuplas->id);
            $mensaje->setMensaje($tuplas->mensaje);
            $mensaje->setEmisor($usuarioRepository->find($tuplas->emisor_id));
            $mensaje->setReceptor($usuarioRepository->find($tuplas->receptor_id));
            $mensaje->setFechaMensaje(new DateTime($tuplas->fecha_mensaje));
            $mensaje->setReportado($tuplas->reportado);

            $mensajes[]=$mensaje;
        }

        return $mensajes;
    }
}
