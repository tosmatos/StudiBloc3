<?php

namespace App\Repository;

use App\Entity\OrderOffer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrderOffer>
 *
 * @method OrderOffer|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderOffer|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderOffer[]    findAll()
 * @method OrderOffer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderOfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderOffer::class);
    }

//    /**
//     * @return OrderOffer[] Returns an array of OrderOffer objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?OrderOffer
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
