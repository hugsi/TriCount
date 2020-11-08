<?php

namespace App\Repository;

use App\Entity\Join;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Join|null find($id, $lockMode = null, $lockVersion = null)
 * @method Join|null findOneBy(array $criteria, array $orderBy = null)
 * @method Join[]    findAll()
 * @method Join[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JoinRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Join::class);
    }

    // /**
    //  * @return Join[] Returns an array of Join objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Join
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
