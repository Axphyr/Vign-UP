<?php

namespace App\Repository;

use App\Entity\Conseil;
use App\Entity\Questionnaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Conseil>
 *
 * @method Conseil|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conseil|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conseil[]    findAll()
 * @method Conseil[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConseilRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conseil::class);
    }

    public function save(Conseil $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Conseil $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getConseilsOfReponses(Questionnaire $questionnaire, array $reponsesNote, bool $isOnAllQuestionnaire): array
    {
        $conseils = [];
        $partie_connecte = $isOnAllQuestionnaire;
        if (is_null($questionnaire->getPartieConnecte()) || 0 == $questionnaire->getPartieConnecte()) {
            $partie_connecte = null;
        }
        $qb = $this->createQueryBuilder('C');
        if (!is_null($partie_connecte)) {
            $qb = $qb->where('C.questionnaire = :id')
                ->andWhere('C.partieConnecte = :partieCo')
                ->orderBy('C.noteMinimale', 'DESC')
                ->setParameter('id', $questionnaire->getId())
                ->setParameter('partieCo', $partie_connecte)
                ->getQuery()->execute();
        } else {
            $qb = $qb->where('C.questionnaire = :id')
                ->andWhere($qb->expr()->isNull('C.partieConnecte'))
                ->orderBy('C.noteMinimale', 'DESC')
                ->setParameter('id', $questionnaire->getId())
                ->getQuery()->execute();
        }

        $noteTotal = $reponsesNote['noteTotal'];
        if (!is_null($partie_connecte) && !$partie_connecte) {
            foreach ($qb as $conseil) {
                if ($conseil instanceof Conseil) {
                    if ($conseil->getNoteMinimale() <= $noteTotal && 0 == count($conseils)) {
                        $conseils[] = $conseil;
                    }
                }
            }
        } else {
            foreach (array_keys($reponsesNote) as $categorie) {
                if ('null' != $categorie) {
                    foreach ($qb as $conseil) {
                        if ($conseil instanceof Conseil) {
                            if (!is_null($conseil->getCategorieQuestion()) && $conseil->getCategorieQuestion()->getNom() == $categorie) {
                                if ($conseil->getNoteMinimale() <= $reponsesNote[$categorie] && !isset($conseils[$categorie])) {
                                    $conseils[$categorie] = $conseil;
                                }
                            } elseif (is_null($conseil->getCategorieQuestion())) {
                                if ($conseil->getNoteMinimale() <= $noteTotal && !isset($conseils['noteTotal'])) {
                                    $conseils['noteTotal'] = $conseil;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $conseils;
    }

//    /**
//     * @return Conseil[] Returns an array of Conseil objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Conseil
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
