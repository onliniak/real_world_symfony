<?php

namespace App\Repository;

use App\Entity\Favorited;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Favorited>
 *
 * @method Favorited|null find($id, $lockMode = null, $lockVersion = null)
 * @method Favorited|null findOneBy(array $criteria, array $orderBy = null)
 * @method Favorited[]    findAll()
 * @method Favorited[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavoritedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Favorited::class);
    }

    public function add(Favorited $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Favorited $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function favorite(int $userID, int $articleID)
    {
        $favorite = new Favorited();
        $favorite->setArticleId($articleID);
        $favorite->setUserId($userID);

        $this->add($favorite);
    }

    public function unfavorite(int $userID, int $articleID)
    {
        return $this->createQueryBuilder('f')
            ->delete()
            ->where('f.user_id = :userID')
            ->andWhere('f.article_id = :articleID');
    }

//    /**
//     * @return Favorited[] Returns an array of Favorited objects
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

//    public function findOneBySomeField($value): ?Favorited
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
