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
        $admin = $userManager->createUser();
        $admin->setUsername('admin@padedam.lt');
        $admin->setFirstName('Admin');
        $admin->setLastName('Website admin');
        $admin->setEmail('admin@padedam.lt');
        $admin->setPlainPassword('pad3dam');
        $admin->setEnabled(true);
        $admin->setBirthday(new  \DateTime('1941-04-14'));
        $admin->setRoles(array('ROLE_ADMIN'));
        $admin->setDescription('I am the admin user of padedam.lt website');
        $userManager->updateUser($admin, true);

        $info = $userManager->createUser();
        $info->setUsername('info@padedam.lt');
        $info->setFirstName('Info');
        $info->setLastName('Website info');
        $info->setEmail('info@padedam.lt');
        $info->setPlainPassword('pad3dam');
        $info->setDescription('I am the info user of padedam.lt website');
        $info->setEnabled(true);
        $info->setBirthday(new  \DateTime('1945-04-14'));
        $info->setRoles(array('ROLE_USER'));
        $userManager->updateUser($info, true);

        $this->addReference('admin', $admin);
        $this->addReference('info', $info);
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