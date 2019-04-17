<?php

namespace Candydate\Repository;

use Candydate\Entity\JobProcess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method JobProcess|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobProcess|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobProcess[]    findAll()
 * @method JobProcess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobProcessRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, JobProcess::class);
    }
}
