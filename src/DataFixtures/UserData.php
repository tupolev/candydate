<?php

namespace Candydate\DataFixtures;

use Candydate\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Psr\Container\ContainerInterface;
use FOS\UserBundle\Doctrine\UserManager;

class UserData extends Fixture
{
    public const USER_MANAGER = 'fos_user.user_manager';

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null): void
    {
        $this->container = $container;
        $this->userManager = $this->container->get(static::USER_MANAGER);
    }

    public function load(ObjectManager $manager): void
    {
        /** @var User $user */
        $user = $this->userManager->createUser();

        $user
            ->setFullName('Admin')
            ->setEnabled(true)
            ->setRoles([User::ROLE_SUPER_ADMIN])
            ->setSalt('34834h3uh668ggej7')
            ->setUsername('admin')
            ->setPlainPassword('admin')
            ->setEmail('admin@candydate.local');
        $manager->persist($user);
        $manager->flush();
    }
}
