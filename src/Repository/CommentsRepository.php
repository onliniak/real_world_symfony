<?php

namespace App\Repository;

use App\Entity\Comments;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comments>
 *
 * @method Comments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comments[]    findAll()
 * @method Comments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comments::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Comments $entity, bool $flush = true): void
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
    public function remove(Comments $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function CreateComment(string $slug, string $author, string $body): void
    {
        $date = $date = new \DateTimeImmutable();

        $comment = new Comments();
        $comment->setArticleSlug($slug);
        $comment->setAuthorId($author);
        $comment->setBody($body);
        $comment->setCreatedAt($date);
        $comment->setUpdatedAt($date);
        $this->add($comment, true);
    }

    /** @return array{author_id: string, body: string, 
     * created_at: \DateTimeImmutable(), updated_at: \DateTimeImmutable(),
     * author: {username: string, bio: string, image: string}} */
    public function ShowComments(string $slug): array
    {
        return $this->createQueryBuilder('c')
            ->select(['c.id', 'c.body', 'c.createdAt', 'c.updatedAt',
            'partial u.{id,username,bio,image} AS author'])
            ->join(User::class, 'u', 'WITH', 'u.username = c.author_id')
            ->where('c.article_slug = :slug')
            ->andWhere('c.id = 1')
            ->setParameter('slug', $slug)
            // ->setParameter('id', $id)
            ->getQuery()
            ->getArrayResult();
    }

    // /**
    //  * @return Comments[] Returns an array of Comments objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Comments
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
