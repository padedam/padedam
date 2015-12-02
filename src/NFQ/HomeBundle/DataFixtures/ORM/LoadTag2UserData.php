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
        $admin = $this->getReference('user');

        $i=1;

        while($this->hasReference('tags'.$i)){
            $tag = $this->getReference('tags'.$i);
            $t2u = new Tag2User();
            $t2u->setUser($admin);
            $t2u->setTag($tag);
            $manager->persist($t2u);
            $i++;
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