<?php

namespace App\Repository;

use App\Entity\Articles;
use App\Repository\Exceptions\IsNullException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
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

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getSingleArticle(string $slug)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY);
    }

    public function listArticles(?string $tag, ?string $author, ?string $favorited, int $limit, int $offset)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.tag = :tag')
            ->andWhere('a.author = :author')
            ->andWhere('a.favorited = :favorited')
            ->setParameter('tag', $tag ?? '*')
            ->setParameter('author', $author ?? '*')
            ->setParameter('favorited', $favorited ?? '*')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws \Doctrine\DBAL\Exception\UniqueConstraintViolationException
     */
    public function createArticle(string $title, string $description, string $body,
                                  string $authorEmail, ?array $tagList): void
    {
        $date = new \DateTimeImmutable();
        $slug = strtolower(preg_replace('/ /', '-', $title));

        $article = new Articles();
        $article->setSlug($slug);
        $article->setTitle($title);
        $article->setDescription($description);
        $article->setBody($body);
        if ($tagList) {
            $article->setTagList($tagList);
        }
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
     * @throws IsNullException
     */
    public function deleteArticle(?string $slug): void
    {
        try {
            $article = $this->findOneBy(['slug' => $slug]);
            $this->remove($article);
        } catch (\Throwable) { // Throwable = Error or Exception
            throw new isNullException('Article not found');
        }
    }

    public function loadTestArticles()
    {
        $createdAt = (new \DateTimeImmutable())
            ->setTimestamp(
                mktime('03', '22','56.637', '02', '18', '2016')
            );
        $updatedAt = (new \DateTimeImmutable())
            ->setTimestamp(
                mktime('03', '48','35.824', '02', '18', '2016')
            );
        $article = new Articles();
        $article->setSlug('how-to-train-your-dragonsss');
        $article->setTitle('How to train your dragon');
        $article->setDescription('Ever wonder how?');
        $article->setBody('It takes a Jacobian');
        $article->setTagList(['dragons', 'training']);
        $article->setFavoritesCount(0);
        $article->setAuthorID('jake@jake.jake');
        $article->setCreatedAt($createdAt);
        $article->setUpdatedAt($updatedAt);
        $this->add($article);

//        unset($article); // clear/reset variable
//        $article = new Articles();
//        $article->setAuthorID();
//        $this->add($article);
    }

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
