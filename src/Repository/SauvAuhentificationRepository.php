<?php

namespace App\Repository;

use App\Entity\SauvAuhentification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SauvAuhentification>
 *
 * @method SauvAuhentification|null find($id, $lockMode = null, $lockVersion = null)
 * @method SauvAuhentification|null findOneBy(array $criteria, array $orderBy = null)
 * @method SauvAuhentification[]    findAll()
 * @method SauvAuhentification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SauvAuhentificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SauvAuhentification::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(SauvAuhentification $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(SauvAuhentification $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return SauvAuhentification[] Returns an array of SauvAuhentification objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SauvAuhentification
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
