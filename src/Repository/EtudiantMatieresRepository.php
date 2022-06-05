<?php

namespace App\Repository;

use App\Entity\EtudiantMatieres;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EtudiantMatieres|null find($id, $lockMode = null, $lockVersion = null)
 * @method EtudiantMatieres|null findOneBy(array $criteria, array $orderBy = null)
 * @method EtudiantMatieres[]    findAll()
 * @method EtudiantMatieres[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtudiantMatieresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EtudiantMatieres::class);
    }

    // /**
    //  * @return EtudiantMatieres[] Returns an array of EtudiantMatieres objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EtudiantMatieres
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
