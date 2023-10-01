<?php

namespace App\Repository;

use App\Entity\MontreCommande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MontreCommande>
 *
 * @method MontreCommande|null find($id, $lockMode = null, $lockVersion = null)
 * @method MontreCommande|null findOneBy(array $criteria, array $orderBy = null)
 * @method MontreCommande[]    findAll()
 * @method MontreCommande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MontreCommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MontreCommande::class);
    }

//    /**
//     * @return MontreCommande[] Returns an array of MontreCommande objects
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

//    public function findOneBySomeField($value): ?MontreCommande
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
