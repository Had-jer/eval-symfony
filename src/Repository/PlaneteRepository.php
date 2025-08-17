<?php

namespace App\Repository;

use App\Entity\Planete;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Planete>
 */
class PlaneteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Planete::class);
    }

    //    /**
    //     * @return Planete[] Returns an array of Planete objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Planete
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function show(int $id, PlaneteRepository $repo)
{
    $planete = $repo->createQueryBuilder('p')
        ->leftJoin('p.satellites', 's')
        ->addSelect('s')
        ->where('p.id = :id')
        ->setParameter('id', $id)
        ->getQuery()
        ->getOneOrNullResult();

    if (!$planete) {
        throw $this->createNotFoundException('Planète non trouvée');
    }

    return $this->render('planete/show.html.twig', [
        'planete' => $planete,
        'satellites' => $planete->getSatellites(),
    ]);
}
}
