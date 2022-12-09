<?php

namespace App\Repository;

use App\Entity\Challenge;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Challenge>
 *
 * @method Challenge|null find($id, $lockMode = null, $lockVersion = null)
 * @method Challenge|null findOneBy(array $criteria, array $orderBy = null)
 * @method Challenge[]    findAll()
 * @method Challenge[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChallengeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Challenge::class);
    }

    public function save(Challenge $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Challenge $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * 
     *
     * @param string $userId
     * @return Challenge[]
     */
    public function findAllActiveByUser(User $user): array
    {

        return $this->createQueryBuilder('c')
            ->innerJoin('c.challengeUsers', 'u', Join::WITH, 'u.user = :user')
            ->andWhere('u.completed = :completed')
            ->setParameter('user', $user->getId())
            ->setParameter('completed', false)
            ->getQuery()
            ->getResult();
    }

    /**
     * 
     *
     * @param string $userId
     * @return Challenge[]
     */
    public function findAllCompletedByUser(User $user): array
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.challengeUsers', 'u', Join::WITH, 'u.user = :user')
            ->andWhere('u.completed = :completed')
            ->setParameter('user', $user->getId())
            ->setParameter('completed', true)
            ->getQuery()
            ->getResult();
    }

    public function findAllInactive(User $user)
    {
        return $this->createQueryBuilder('c')
            ->select('c', 'author')
            ->innerJoin('c.author', 'author')
            ->where('c NOT IN (SELECT (cu.challenge) FROM App:UserChallenge cu WHERE cu.user = :user)')
            ->setParameter('user', $user->getId())
            ->getQuery()
            ->getResult();
    }

    /**
     * 
     *
     * @param mixed $id
     * @return Challenge|null
     */
    public function findWithUsers($id)
    {
        return $this->createQueryBuilder('c')
            ->select('c', 'users')
            ->innerJoin('c.challengeUsers', 'users')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    //    /**
    //     * @return Challenge[] Returns an array of Challenge objects
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

    //    public function findOneBySomeField($value): ?Challenge
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
