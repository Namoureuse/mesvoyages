<?php

namespace App\Repository;

use App\Entity\Visite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Visite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Visite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Visite[]    findAll()
 * @method Visite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Visite::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Visite $entity, bool $flush = true): void
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
    public function remove(Visite $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Visite[] Returns an array of Visite objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Visite
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    
    /**
     * Retourne toutes les visites triées sur un champ.
     * @param type $champ
     * @param type $ordre
     * @return Visite[]
     */
    public function findAllOrderBy($champ, $ordre): array {
        return $this->createQueryBuilder('v')
                ->orderBy('v.'.$champ, $ordre)
                ->getQuery()
                ->getResult();
    }
    
    /**
     * Enregistrement où un champ est égal à une valeur
     * @param type $champ
     * @param type $valeur
     * @return Visite[]
     */
    public function findByEqualValue($champ, $valeur): array {
        if($valeur=="") {
            return $this->createQueryBuilder('v')
                    ->orderBy('v.'.$champ, 'ASC')
                    ->getQuery()
                    ->getResult();
        } else {
            return $this->createQueryBuilder('v')
                    ->where('v.'.$champ.'=:valeur')
                    ->setParameter('valeur', $valeur)
                    ->orderBy('v.datecreation', 'DESC')
                    ->getQuery()
                    ->getResult();
        }
    }
}
