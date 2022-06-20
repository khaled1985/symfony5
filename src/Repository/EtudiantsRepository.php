<?php

namespace App\Repository;

use App\Entity\Etudiants;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Etudiants|null find($id, $lockMode = null, $lockVersion = null)
 * @method Etudiants|null findOneBy(array $criteria, array $orderBy = null)
 * @method Etudiants[]    findAll()
 * @method Etudiants[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtudiantsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Etudiants::class);
    }

    // /**
    //  * @return Etudiants[] Returns an array of Etudiants objects
    //  */

    public function findByExampleField()
    {
        return $this->createQueryBuilder('e')
            ->select("e.nom, e.age")
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }


    
    public function nbrEtudiantNom($nomEtudiant)
    { 

        return $this->createQueryBuilder('p')
        ->select('count(p.nom) as nbrnom')
        ->where('p.nom= :nomEtudiant')
        ->setParameter('nomEtudiant',$nomEtudiant)
        ->getQuery()
        // ->getOneOrNullResult();  return array result
        ->getSingleScalarResult();  //return variable
    }



    public function findLatest()
    {
        // $this->createQueryBuilder('p')
        //     ->leftJoin('p.noms', 'n')
        //     ->leftJoin('p.initiales', 'i')
        //     ->leftJoin('p.nomclassements', 'nc')
        //     ->where('p.id = :id_pays')
        //     ->addSelect('n')
        //     ->addSelect('i')
        //     ->addSelect('nc')
        //     ->setParameter('id_pays', $id_pays);

        return  $this->createQueryBuilder('p')
            ->leftJoin('p.CinEtudiants', 'n')

            ->addSelect('n')



            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Etudiants
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
