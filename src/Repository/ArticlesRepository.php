<?php

namespace App\Repository;

use App\Entity\Articles;
use App\Entity\User;
use App\Service\APIResponses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

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
        $response = new APIResponses();
        $article = $this->createQueryBuilder('a')
            ->select(['a.slug', 'a.title', 'a.description', 'a.body', 'a.tagList',
                'a.createdAt', 'a.updatedAt', 'a.favoritesCount',
                'u.username', 'u.bio', 'u.image', ])
            ->join(User::class, 'u')
            ->where('u.username = a.authorID')
            ->andWhere('a.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY);

        if (is_null($article)) {
            return null;
        } else {
            return $response->articleResponse($article, $article["username"]);
        }
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
                                  string $authorEmail, ?array $tags): ?array
    {
        $date = new \DateTimeImmutable();
        $slug = strtolower(preg_replace('/ /', '-', $title));

        $article = new Articles();
        $article->setSlug($slug);
        $article->setTitle($title);
        $article->setDescription($description);
        $article->setBody($body);
        if ($tags) {
            $article->setTags($tags);
        }
        $article->setCreatedAt($date);
        $article->setUpdatedAt($date);
        $article->setFavoritesCount(0);
        $article->setAuthorID($authorEmail);
        $this->add($article);

        return $this->getSingleArticle($slug, $authorEmail);
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

    public function getTags(): array
    {
        return $this->createQueryBuilder('a')
            ->select(['a.tagList'])
            ->getQuery()
            ->getResult()[0]['tagList'];
    }

    public function favorite(string $favorited, int $id)
    {
        return $this->createQueryBuilder('a')
            ->update(['favorited', $favorited])
            ->setParameter('id', $id)
            ->where('a.id = :id')
            ->getQuery()
            ->getResult();
        // tu będzie where in lub where like
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
