<?php

namespace src\NFQ\HomeBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use NFQ\ReviewsBundle\Entity\Review;
use NFQ\ReviewsBundle\Entity\Thanks;

class LoadReviewData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $users = ['admin', 'info'];
        foreach ($users as $user) {
            $ref = $this->getReference($user);
            $thanks = new Thanks();
            $thanks->setNumber(rand(1, 10));
            $thanks->setHelper($ref);
            $manager->persist($thanks);
        }
        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 5;
    }
}