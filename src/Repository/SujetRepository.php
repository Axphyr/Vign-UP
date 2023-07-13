<?php

namespace App\Repository;

use App\Entity\Sujet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sujet>
 *
 * @method Sujet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sujet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sujet[]    findAll()
 * @method Sujet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SujetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sujet::class);
    }

    public function save(Sujet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Sujet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function searchByTitle(string $title = '', int $page = 1): array
    {
        $qb = $this->createQueryBuilder('s')
            ->where('s.titre like :subjectTitle')
            ->setParameter('subjectTitle', "%$title%")
            ->orderBy('s.lastMessageDate', 'DESC');
        if (1 == $page) {
            $firstTopic = 0;
        } else {
            $firstTopic = ($page - 1) * 10;
        }

        $query = $qb->getQuery()->setMaxResults(10)->setFirstResult($firstTopic);

        return $query->execute();
    }

    public function searchByAuthor(string $pseudo = '', int $page = 1): array
    {
        $qb = $this->createQueryBuilder('s')
            ->leftJoin('s.user', 'user')
            ->addSelect('user')
            ->where('user.pseudo like :pseudo')
            ->orderBy('s.lastMessageDate', 'DESC')
            ->setParameter('pseudo', "%$pseudo%");
        if (1 == $page) {
            $firstTopic = 0;
        } else {
            $firstTopic = ($page - 1) * 10;
        }
        $s = $qb->getQuery()->setMaxResults(10)->setFirstResult($firstTopic)->execute();
        if (0 == sizeof($s)) {
            return [];
        } else {
            return $s;
        }
    }

/*
    public function searchByNewestMessage(): array
    {
        $qb = $this->createQueryBuilder('s')
            ->leftJoin('s.messages', 'm');
        $s = $qb->getQuery()->execute();

        return $s;
    }
*/
//    /**
//     * @return Sujet[] Returns an array of Sujet objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sujet
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
