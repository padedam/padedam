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
        $users = ['admin', 'info'];
        $text = 'Sed vehicula felis vehicula odio porta rhoncus. Duis mattis nec risus in sodales. Vestibulum dictum tincidunt nisl, a pellentesque tellus. Ut non gravida quam. Proin dui diam, scelerisque et varius tempor, vestibulum quis diam. Duis lacinia blandit elit eu elementum. Nam imperdiet dui sapien, lacinia tristique elit suscipit sit amet. Vivamus laoreet, magna a consectetur sagittis, ligula metus tempus dolor, eget pulvinar felis sapien a ante. Proin volutpat mattis nisi vitae imperdiet. ';
        $tags = [$this->getReference('tags_admin1'), $this->getReference('tags_info3')];

        foreach ($users as $user) {
            $ref = $this->getReference($user);

            $assistanceRequest = new AssistanceRequest();
            $assistanceRequest->setStatus(AssistanceRequest::STATUS_WAITING);
            $assistanceRequest->setOwner($ref);
            $assistanceRequest->setDate(new \DateTime('yesterday'));
            $assistanceRequest->setShortDescription('assistance request');
            $assistanceRequest->setLongDescription($text);
            $assistanceRequest->addTag($tags[0]);
            $assistanceRequest->addTag($tags[1]);

            $manager->persist($assistanceRequest);
        }
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