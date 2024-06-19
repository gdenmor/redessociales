<?php

namespace App\Repository;

use App\Entity\TiposPublicacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TiposPublicacion>
 *
 * @method TiposPublicacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method TiposPublicacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method TiposPublicacion[]    findAll()
 * @method TiposPublicacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TiposPublicacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TiposPublicacion::class);
    }

    //    /**
    //     * @return TiposPublicacion[] Returns an array of TiposPublicacion objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?TiposPublicacion
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
