<?php

namespace Candydate\Repository;

use Candydate\Entity\JobProcessLogEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method JobProcessLogEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobProcessLogEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobProcessLogEntry[]    findAll()
 * @method JobProcessLogEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobProcessLogRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, JobProcessLogEntry::class);
    }
}
