<?php

namespace App\Repository;

use App\Entity\City;
use App\Entity\Country;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method City|null find($id, $lockMode = null, $lockVersion = null)
 * @method City|null findOneBy(array $criteria, array $orderBy = null)
 * @method City[]    findAll()
 * @method City[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }

 
    public function getCity($idCountry)
    {
        
        return $this->createQueryBuilder('s')
            
            ->where('s.country = :idParent')->setParameter('idParent',  $idCountry)
            ->getQuery()
            ->getResult();
    }
    
    public function findByCountryOrderedByAscName(Country $country): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.country = :country')
            ->setParameter('country', $country)
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    } 
}
