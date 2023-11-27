<?php

namespace App\Repository;

use App\Entity\ReponseOffre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReponseOffre>
 *
 * @method ReponseOffre|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReponseOffre|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReponseOffre[]    findAll()
 * @method ReponseOffre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReponseOffreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReponseOffre::class);
    }

//    /**
//     * @return ReponseOffre[] Returns an array of ReponseOffre objects
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

//    public function findOneBySomeField($value): ?ReponseOffre
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
