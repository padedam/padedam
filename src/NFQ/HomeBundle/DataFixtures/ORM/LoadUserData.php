<?php

namespace src\NFQ\HomeBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class LoadUserData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{

    /**
     * @var ContainerAwareInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->createUser();
        $user->setUsername('admin@padedam.lt');
        $user->setFirstName('Website admin');
        $user->setEmail('admin@padedam.lt');
        $user->setPlainPassword('pad3dam');
        $user->setEnabled(true);
        $user->setBirthday(new  \DateTime('1941-04-14'));
        $user->setRoles(array('ROLE_ADMIN'));
        $userManager->updateUser($user, true);

        $this->addReference('user', $user);
        //$this->getReference('sportas')
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }
}