<?php

namespace App\Repository;

use App\Entity\Member;
use App\Entity\MemberTraining;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MemberTraining|null find($id, $lockMode = null, $lockVersion = null)
 * @method MemberTraining|null findOneBy(array $criteria, array $orderBy = null)
 * @method MemberTraining[]    findAll()
 * @method MemberTraining[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MemberTrainingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MemberTraining::class);
    }

    public function removeMemberTraining(){
        $qb = $this->createQueryBuilder('m')
        ->delete('member_training')
        ->where('m.member_id = :member')
        ->setParameter('member', null);
    }

    public function getCursemember($id){
        return $this->createQueryBuilder('k')
            ->select('k')
            ->innerJoin('App\Entity\Member','mem','WITH', 'k.member = mem.id')
            ->innerJoin('App\Entity\Training','train','WITH', 'k.training = train.id')
            ->where('train.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    public function test()
    {
        return $this->createQueryBuilder('k')
            ->select('k')
            ->innerJoin('App\Entity\Trainer','s','WITH','k.trainer = s.id')
            ->getQuery()
            ->getResult();
    }



    // /**
    //  * @return MemberTraining[] Returns an array of MemberTraining objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MemberTraining
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
