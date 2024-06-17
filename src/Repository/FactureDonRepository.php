<?php

namespace App\Repository;

use App\Entity\FactureDon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FactureDon>
 *
 * @method FactureDon|null find($id, $lockMode = null, $lockVersion = null)
 * @method FactureDon|null findOneBy(array $criteria, array $orderBy = null)
 * @method FactureDon[]    findAll()
 * @method FactureDon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FactureDonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FactureDon::class);
    }

//    /**
//     * @return FactureDon[] Returns an array of FactureDon objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FactureDon
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
