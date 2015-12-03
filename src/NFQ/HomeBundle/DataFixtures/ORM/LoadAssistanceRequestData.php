<?php

namespace src\NFQ\HomeBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use NFQ\AssistanceBundle\Entity\AssistanceRequest;

class LoadAssistanceRequestData extends AbstractFixture implements OrderedFixtureInterface
{


    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $admin = $this->getReference('user');
        $tag = $this->getReference('tags1');

        $text = ' Sed vehicula felis vehicula odio porta rhoncus. Duis mattis nec risus in sodales. Vestibulum dictum tincidunt nisl, a pellentesque tellus. Ut non gravida quam. Proin dui diam, scelerisque et varius tempor, vestibulum quis diam. Duis lacinia blandit elit eu elementum. Nam imperdiet dui sapien, lacinia tristique elit suscipit sit amet. Vivamus laoreet, magna a consectetur sagittis, ligula metus tempus dolor, eget pulvinar felis sapien a ante. Proin volutpat mattis nisi vitae imperdiet. ';
        $assistanceRequest = new AssistanceRequest();
        $assistanceRequest->setStatus(AssistanceRequest::STATUS_WAITING);
        $assistanceRequest->setOwner($admin);
        $assistanceRequest->setDate(new \DateTime('yesterday'));
        $assistanceRequest->setShortDescription('testing assistance request');
        $assistanceRequest->setLongDescription($text);
        $assistanceRequest->addTag($tag);

        $manager->persist($assistanceRequest);
        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 4;
    }
}