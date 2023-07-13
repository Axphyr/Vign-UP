<?php

namespace App\Repository;

use App\Entity\VSL;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VSL>
 *
 * @method VSL|null find($id, $lockMode = null, $lockVersion = null)
 * @method VSL|null findOneBy(array $criteria, array $orderBy = null)
 * @method VSL[]    findAll()
 * @method VSL[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VSLRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VSL::class);
    }

    public function save(VSL $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(VSL $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return VSL[] Returns an array of VSL objects
     */
    public function findByApprovedValue($value): array
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.approved = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?VSL
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
