<?php

namespace App\Repository;

use App\Entity\Timeslot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Timeslot>
 *
 * @method Timeslot|null find($id, $lockMode = null, $lockVersion = null)
 * @method Timeslot|null findOneBy(array $criteria, array $orderBy = null)
 * @method Timeslot[]    findAll()
 * @method Timeslot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimeslotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Timeslot::class);
    }

    //    /**
    //     * @return Timeslot[] Returns an array of Timeslot objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Timeslot
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findTimesForService(int $serviceId)
    {
        return $this->createQueryBuilder('bt')
            ->join('bt.allowedServices', 's')
            ->where('s.id = :serviceId')
            ->setParameter('serviceId', $serviceId)
            ->getQuery()
            ->getResult();
    }
}
