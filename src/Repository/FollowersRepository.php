<?php

namespace App\Repository;

use App\Entity\Followers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @extends ServiceEntityRepository<Followers>
 *
 * @method Followers|null find($id, $lockMode = null, $lockVersion = null)
 * @method Followers|null findOneBy(array $criteria, array $orderBy = null)
 * @method Followers[]    findAll()
 * @method Followers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FollowersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, Followers::class);
        $this->security = $security->getUser()?->getUserIdentifier() ?? '';
    }

    public function save(Followers $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Followers $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function follow(string $userEmail, string $profileUsername)
    {
        $profile = $this->findOneBy(['user1' => $userEmail, 'user2' => $profileUsername]);
        if (is_null($profile)) {

            $follower = new Followers();
            $follower->setUser1($userEmail);
            $follower->setUser2($profileUsername);

            $this->save($follower, true);
        }
    }

    public function unfollow(string $username)
    {
        $profile = $this->findOneBy(['user1' => $this->security, 'user2' => $username]);
        if (!is_null($profile)) {
            $this->remove($profile, true);
        }
    }

    //    /**
    //     * @return Followers[] Returns an array of Followers objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Followers
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
