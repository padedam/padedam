<?php

namespace src\NFQ\HomeBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use NFQ\AssistanceBundle\Entity\Tag2User;

class LoadTag2UserData extends AbstractFixture implements OrderedFixtureInterface
{


    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $users = ['admin', 'info'];

        foreach ($users as $user) {
            $a = 1;

            $ref = $this->getReference($user);
            while ($this->hasReference('tags_' . $user . $a)) {
                $tag = $this->getReference('tags_' . $user . $a);
                $t2u = new Tag2User();
                $t2u->setUser($ref);
                $t2u->setTag($tag);
                $manager->persist($t2u);
                $a++;
            }
            $a = 1;
        }

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 3;
    }
}