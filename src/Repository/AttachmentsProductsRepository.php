<?php

namespace App\Repository;

use App\Entity\AttachmentsProducts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AttachmentsProducts>
 *
 * @method AttachmentsProducts|null find($id, $lockMode = null, $lockVersion = null)
 * @method AttachmentsProducts|null findOneBy(array $criteria, array $orderBy = null)
 * @method AttachmentsProducts[]    findAll()
 * @method AttachmentsProducts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AttachmentsProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AttachmentsProducts::class);
    }

//    /**
//     * @return AttachmentsProducts[] Returns an array of AttachmentsProducts objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AttachmentsProducts
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
