<?php

namespace App\Repository;

use App\Entity\Dechets;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Dechets>
 *
 * @method Dechets|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dechets|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dechets[]    findAll()
 * @method Dechets[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DechetsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dechets::class);
    }  public function findBySearchQuery($searchQuery)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.type LIKE :query')
            ->setParameter('query', '%' . $searchQuery . '%')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Dechets[] Returns an array of Dechets objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Dechets
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function countByType()
{
    $qb = $this->createQueryBuilder('p')
        ->select('p.type, COUNT(p.id) AS typeCount')
        ->groupBy('p.type');

    return $qb->getQuery()->getResult();
}

public function triecroissant()
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.quantite', 'ASC')

            ->getQuery()
            ->getResult();

    }
    public function triedecroissant()
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.quantite','DESC')
            ->getQuery()
            ->getResult();
    }
}
