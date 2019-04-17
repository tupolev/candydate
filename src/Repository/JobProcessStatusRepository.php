<?php

namespace Candydate\Repository;

use Candydate\Entity\JobProcessStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method JobProcessStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobProcessStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobProcessStatus[]    findAll()
 * @method JobProcessStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobProcessStatusRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, JobProcessStatus::class);
    }
}
