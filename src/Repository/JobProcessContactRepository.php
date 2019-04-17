<?php

namespace Candydate\Repository;

use Candydate\Entity\JobProcessContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method JobProcessContact|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobProcessContact|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobProcessContact[]    findAll()
 * @method JobProcessContact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobProcessContactRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, JobProcessContact::class);
    }
}
