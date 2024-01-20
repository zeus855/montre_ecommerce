<?php

namespace App\Repository;

use App\Entity\Montre;
use App\Model\Search;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Montre>
 *
 * @method Montre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Montre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Montre[]    findAll()
 * @method Montre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MontreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Montre::class);
    }

    public function searchByTerm(?string $term): array
    {
        //  On crée un objet QueryBuilder associé à l'entité Montre 'm' qui represente l'entité Montre
        $qb = $this->createQueryBuilder('m');

        // On vérifie si le terme de recherche est fourni.
        if ($term) {
            $qb->where('m.titre LIKE :term')
            // On utilise setParameter pour lier le paramètre :term à la valeur %{$term}% 
                ->setParameter('term', "%" . $term . "%");
        }

        return $qb->getQuery()->getResult();
    }


    public function searchByCriteria(Search $search): array
    {

        /*
            EN SQL : 
            SELECT * 
            FROM montre m
            INNER JOIN categorie c ON (m.categorie_id = c.id)
            WHERE c.id = 2 AND m.titre LIKE '%term%'
        */

        $qb = $this->createQueryBuilder('m');
        
        $qb->innerJoin('m.categorie', 'c');

        if ($search->getRecherche()) {
            $qb->andWhere('m.titre LIKE :term')
            ->setParameter('term', '%'. $search->getRecherche() .'%');
        }
        if ($search->getCategorie()) {
            $qb->andWhere('c.id = :categorie')
            ->setParameter('categorie', $search->getCategorie()->getId());
        }


        return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return Montre[] Returns an array of Montre objects
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

    //    public function findOneBySomeField($value): ?Montre
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
