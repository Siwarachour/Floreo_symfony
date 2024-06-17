<?php

namespace App\Repository;

use App\Entity\ReservationDechets;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReservationDechets>
 *
 * @method ReservationDechets|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReservationDechets|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReservationDechets[]    findAll()
 * @method ReservationDechets[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationDechetsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReservationDechets::class);
    }

//    /**
//     * @return ReservationDechets[] Returns an array of ReservationDechets objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ReservationDechets
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
