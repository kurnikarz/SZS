<?php

namespace App\Repository;

use App\Entity\SuperAdmin;
use Doctrine\Common\Persistence\ManagerRegistry;

use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;


/**
 * @method SuperAdmin|null find($id, $lockMode = null, $lockVersion = null)
 * @method SuperAdmin|null findOneBy(array $criteria, array $orderBy = null)
 * @method SuperAdmin[]    findAll()
 * @method SuperAdmin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SuperAdminRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SuperAdmin::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.v
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof SuperAdmin) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }
    public function CountRoot(){
        $qb = $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
        return $qb;
    }

    public function GetRootPreview($MaxRoots){
        $query = $this->createQueryBuilder('u')
            ->select('u')
            ->orderBy('u.id')
            ->setFirstResult(0)
            ->setMaxResults($MaxRoots)
            ->getQuery()
            ->getArrayResult();
        return $query;

    }
    //  /**
    //   * @return SuperAdmin[] Returns an array of SuperAdmin objects
    //   */

    // public function findByExampleField($value)
    // {
    //     return $this->createQueryBuilder('s')
    //         ->andWhere('s.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->orderBy('s.id', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }
    

    /*
    public function findOneBySomeField($value): ?SuperAdmin
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
