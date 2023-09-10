<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Contact>
 *
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }
   
   /**
    * @return Contact[] Returns an array of Contact objects
    */
   public function findByFullname($value): array
   {

        $qb = $this->createQueryBuilder('c');

        if (!empty($value['lastname'])) {
            $qb->andWhere('c.lastname LIKE :lastName')
                ->setParameter('lastName', '%' . $value['lastname'] . '%');
        }
        if (!empty($value['firstname'])) {
            $qb->andWhere('c.firstname LIKE :firstName')
                ->setParameter('firstName', '%' . $value['firstname'] . '%');
        }

       return $qb
           ->orderBy('c.id', 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }

    // peux servir 

   public function findAllPlacesInRegion() {
        $queryBuilder = $this->createQueryBuilder('p')
            ->leftJoin('p.region', 'r')
            ->where('r.name like :region_name' )
            ->setParameter('region_name', 'Республика Крым');
        return $queryBuilder->getQuery()->getResult();
    }


//    public function findOneBySomeField($value): ?Contact
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
