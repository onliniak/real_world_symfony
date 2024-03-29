<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Followers;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function add(User $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(User $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function serialize(string $email, string $username, string $password): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setUsername($username);
        $user->setRoles(['ROLE_USER']);

        return $user;
    }
    
    public function getUserByUsername(string $username): User
    {
        return $this->createQueryBuilder('u')
            // ->select(['u.email', 'u.username', 'u.bio', 'u.image'])
            ->select()
            ->where('u.username = :userID')
            ->setParameter('userID', $username)
            ->getQuery()
            ->getSingleResult();
    }

    public function getUserByLoginPassword(string $email, string $password): ?User
    {
        $user = $this->createQueryBuilder('u')
            ->select()
            ->where('u.email = :userEmail')
            ->setParameter('userEmail', $email)
            ->getQuery()
            ->getSingleResult();

        if ($user->verifyPassword($password, $user->getPassword())) {
            return $user;
        } else {
            return null;
        }
    }

    public function updateUser(Security $security, object $json): User
    {
        $user = $this->getUserByUsername($security->getUser()->getUserIdentifier());
        if (isset($json->email)) {
            $user->setEmail($json->email);
        }
        if (isset($json->username)) {
            $user->setUsername($json->username);
        }
        if (isset($json->password)) {
            $user->setPassword($json->password);
        }
        if (isset($json->image)) {
            $user->setImage($json->image);
        }
        if (isset($json->bio)) {
            $user->setBio($json->bio);
        }
        $this->add($user);

        return $user;
    }

    public function getProfile(string $profileUser, ?string $authUser = null)
    {
        return $this->createQueryBuilder('u')
            ->select('u.username, u.bio, u.image')
            ->addSelect('
            (
            CASE WHEN f.user1 = :authOR AND f.user2 = :user
            THEN :true
            ELSE :false
            END) AS following
            ')
            ->leftJoin(Followers::class, 'f')
            ->where('u.username = :user')
            ->setParameter('user', $profileUser)
            ->setParameter('true', "true")
            ->setParameter('false', "false")
            ->setParameter('authOR', $authUser)
            ->getQuery()
            ->getSingleResult();
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
