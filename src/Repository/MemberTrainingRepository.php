<?php

namespace App\Repository;

use App\Entity\Member;
use App\Entity\MemberTraining;
use App\Entity\Training;
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

    /*
    public function removeMemberTraining(Member $memberId, Training $trainingId){
        $qb = $this->createQueryBuilder('m')
        ->delete('MemberTraining')
        ->where('m.member = :member')
            ->andWhere('m.training = :training')
        ->setParameter('member', $memberId)
        ->setParameter('training', $trainingId);
        $qb->getFirstResult();
        return $qb;
    }
    */

    public function removeMemberTraining($memberId, $trainingId){
        $em = $this->getEntityManager();
        //$query = $em->createQuery('DELETE FROM App\Entity\MemberTraining u WHERE u.member is null');
        $query = $em->createQuery('DELETE FROM App\Entity\MemberTraining m WHERE m.member = :member AND m.training = :training');
        $query->setParameter('member', $memberId);
        $query->setParameter('training', $trainingId);
        $query->getResult();
        return $query;
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
