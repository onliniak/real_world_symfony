<?php

namespace App\Repository;

use App\Entity\Articles;
use App\Entity\Favorited;
use App\Entity\Followers;
use App\Entity\Tags;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @method Articles|null find($id, $lockMode = null, $lockVersion = null)
 * @method Articles|null findOneBy(array $criteria, array $orderBy = null)
 * @method Articles[]    findAll()
 * @method Articles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticlesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, ?Security $security)
    {
        parent::__construct($registry, Articles::class);
        $this->security = $security->getUser()?->getUserIdentifier();
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

    public function getSingleArticle(string $slug)
    {
        $query = $this->createQueryBuilder('a')
            ->select([
                'a.slug', 'a.title', 'a.description', 'a.body',
                'a.createdAt', 'a.updatedAt', 'a.favoritesCount',
                'partial u.{id,username,bio,image} AS author'
            ])
            ->addSelect('
            (
            CASE WHEN f.user_id = :authOR AND f.article_slug = a.slug
            THEN :true
            ELSE :false
            END) AS favorited
            ')
            ->join(User::class, 'u', 'WITH', 'u.username = a.authorID')
            ->leftJoin(Favorited::class, 'f', 'WITH', 'f.user_id = u.username')
            ->where('a.slug = :slug')
            ->setParameter('true', "true")
            ->setParameter('false', "false")
            ->setParameter('authOR', $this->security)
            ->setParameter('slug', $slug)
            ->setMaxResults(1)
            ->getQuery()
            ->getArrayResult();

        return '{"article": ' . json_encode(array_merge($query[0], ['tagList' =>
        $this->_em
            ->getRepository('App\Entity\Tags')
            ->getTagsFromSingleArticle($query[0]['slug'])])) . '}';
    }

    public function listArticles(int $limit, int $offset, ?string $tag, 
    ?string $author, ?string $favorited, ?bool $feed)
    {
        $query = $this->createQueryBuilder('a')
            ->select([
                'a.slug, a.title, a.description, 
            a.body, a.createdAt, a.updatedAt, a.favoritesCount',
                'partial u.{id,username,bio,image} AS author'
            ])
            ->addSelect('
            (
            CASE WHEN f.user_id = u.username AND f.article_slug = a.slug
            THEN :true
            ELSE :false
            END) AS favorited
            ')
            ->join(User::class, 'u', 'WITH', 'u.username = a.authorID')
            ->leftJoin(Favorited::class, 'f', 'WITH', 'f.user_id = u.username')
            ->setParameter('true', "true")
            ->setParameter('false', "false");
            if (!empty($tag)) {
                $query = $query->join(Tags::class, 't', 'WITH', 't.article_slug = a.slug')
                ->andWhere('t.tag = :tag')
                ->setParameter('tag', $tag);
            }
            if (!empty($author)) {
                $query = $query
                ->andWhere('u.username = :author')
                ->setParameter('author', $author);
            }
            if (!empty($favorited)) {
                $query = $query
                ->andWhere('f.user_id = :favorited')
                ->setParameter('favorited', $favorited);
            }
            if ($feed) {
                $query = $query
                ->join(Followers::class, 'fl', 'WITH', 'fl.user1 = :authOR AND fl.user2 = u.email')
                ->setParameter('authOR', $this->security);
            }
        $query = $query->setMaxResults($limit)
        ->setFirstResult($offset)
        ->getQuery()
        ->getArrayResult();

        $articlesCount = 0;
        $articlesArray = '{"articles":[';
        foreach ($query as $article) {
            // GROUP_CONCAT in pure PHP
            $articlesArray .= json_encode(array_merge($article, ['tagList' =>
            $this->_em
                ->getRepository('App\Entity\Tags')
                ->getTagsFromSingleArticle($article['slug'])]));
            $articlesCount += 1;
            if ($articlesCount > 1) {
                $articlesArray .= ',';
            }
        }
        $articlesArray .= '], "articlesCount":';
        $articlesArray .= $articlesCount;
        $articlesArray .= '}';
        return $articlesArray;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws \Doctrine\DBAL\Exception\UniqueConstraintViolationException
     */
    public function createArticle(
        string $title,
        string $description,
        string $body,
        string $authorEmail
    ): void {
        $date = new \DateTimeImmutable();
        $slug = strtolower(preg_replace('/ /', '-', $title));

        $article = new Articles();

        $article->setSlug($slug);
        $article->setTitle($title);
        $article->setDescription($description);
        $article->setBody($body);
        $article->setCreatedAt($date->format('Y-m-d\TH:i:s.v\Z'));
        $article->setUpdatedAt($date->format('Y-m-d\TH:i:s.v\Z'));
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
    public function deleteArticle(?string $slug, TagsRepository $tagsRepository): void
    {
        $article = $this->findOneBy(['slug' => $slug]);
        if (!is_null($article)) {
            $this->remove($article);
            $tagsRepository->deleteTagsFromSingleArticle($slug);
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
