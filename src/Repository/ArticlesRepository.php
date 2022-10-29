<?php

namespace App\Repository;

use App\Entity\Articles;
use App\Entity\Favorited;
use App\Entity\Tags;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Articles|null find($id, $lockMode = null, $lockVersion = null)
 * @method Articles|null findOneBy(array $criteria, array $orderBy = null)
 * @method Articles[]    findAll()
 * @method Articles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticlesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Articles::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Articles $entity, bool $flush = true): void
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
    public function remove(Articles $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getSingleArticle(string $slug): ?array
    {
        $query = $this->createQueryBuilder('a')
            ->select(['a.slug', 'a.title', 'a.description', 'a.body',
            'a.createdAt', 'a.updatedAt', 'a.favoritesCount',
            'u.username', 'u.bio', 'u.image'])
            ->join(User::class, 'u', 'WITH', 'u.username = a.authorID')
            ->where('a.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult();

            if (!empty($query)) {
                // Move user table/query to user subarray
                $user = ['author' => [
                'username' => $query['username'],
                'bio'      => $query['bio'],
                'image'    => $query['image']
                 ]];
                // Convert date + time to ISO 8601
                $query['createdAt'] = $query['createdAt']->format('Y-m-d\TH:i:s.v\Z');
                $query['updatedAt'] = $query['updatedAt']->format('Y-m-d\TH:i:s.v\Z');
                // Remove from array
                unset($query['username'],
                $query['bio'], $query['image']);

                return array_merge($query, $user);
            }
            return $query;
    }

    public function listArticles(int $limit, int $offset, ?string $tag, ?string $author, ?string $favorited): mixed
    {
        return $this->createQueryBuilder('a')
            ->select(['a.slug', 'a.title', 'a.description', 'a.body', 'a.tagList',
                'a.createdAt', 'a.updatedAt', 'a.favorited', 'a.favoritesCount', 'a.authorID', ])
            ->andWhere('a.tag = :tag')
            ->andWhere('a.author = :author')
            ->andWhere('a.favorited = :favorited')
            ->setParameter('tag', $tag)
            ->setParameter('author', $author ?? '*')
            ->setParameter('favorited', $favorited ?? '*')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws \Doctrine\DBAL\Exception\UniqueConstraintViolationException
     */
    public function createArticle(string $title, string $description, string $body,
                                  string $authorEmail): void
    {
        $date = new \DateTimeImmutable();
        $slug = strtolower(preg_replace('/ /', '-', $title));

        $article = new Articles();

        $article->setSlug($slug);
        $article->setTitle($title);
        $article->setDescription($description);
        $article->setBody($body);
        $article->setCreatedAt($date);
        $article->setUpdatedAt($date);
        $article->setFavoritesCount(0);
        $article->setAuthorID($authorEmail);
        $this->add($article);
    }

    public function updateArticle(?string $title, ?string $description, ?string $body)
    {
        $query = $this->getEntityManager()->createQueryBuilder()
            ->update('a');
        if ($title) {
            $query->set('a.title', $title)
                  ->set('a.slug', $title);
        }
        if ($description) {
            $query->set('a.description', $description);
        }
        if ($body) {
            $query->set('a.body', $body);
        }

        return $query->getQuery();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function deleteArticle(?string $slug): void
    {
        $article = $this->findOneBy(['slug' => $slug]);
        if (!is_null($article)) {
            $this->remove($article);
        }
    }

    // public function getTags(): array
    // {
    //     return $this->createQueryBuilder('a')
    //         ->select(['a.tagList'])
    //         ->getQuery()
    //         ->getResult()[0]['tagList'];
    // }

    // public function favorite(string $favorited, int $id)
    // {
    //     return $this->createQueryBuilder('a')
    //         ->update(['favorited', $favorited])
    //         ->setParameter('id', $id)
    //         ->where('a.id = :id')
    //         ->getQuery()
    //         ->getResult();
    //     // tu będzie where in lub where like
    // }

    // /**
    //  * @return ArticlesService[] Returns an array of Articles objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Articles
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
