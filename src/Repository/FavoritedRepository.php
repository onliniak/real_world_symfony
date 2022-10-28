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

    public function favorite(string $articleSlug, string $userID): void
    {
        $favorite = new Favorited();
        $favorite->setArticleSlug($articleSlug);
        $favorite->setUserId($userID);

        $this->add($favorite, true);
    }

    public function unfavorite( string $articleSlug, string $userID): void
    {
        $this->remove(
            $this->getEntityManager()
        ->getRepository(Favorited::class, 'f')
        ->findOneBy(['article_slug' => $articleSlug, 
                     'user_id'      => $userID]), true
        );
    }

    public function getFavoritesFromSingleArticle(string $slug): array
    {
        $query = $this->createQueryBuilder('f')
            ->select('f.user_id')
            ->where('f.article_slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getScalarResult();

        return ['favorited' => array_column($query, 'user_id')];
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
