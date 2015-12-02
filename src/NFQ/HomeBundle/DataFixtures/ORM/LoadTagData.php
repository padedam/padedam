<?php

namespace src\NFQ\HomeBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use NFQ\AssistanceBundle\Entity\Tags;

class LoadTagData  extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $tags = [
            'statybos'=>[
                'tinkas',
                'glaistas',
                'cementas'
            ],
            'elektra'=>[
                'rozetė',
                'lemputė',
                'lituoti'
            ],
            'kompiuteriai'=>[
                'diskas',
                'windows',
                'linux',
                'dox',
                'klaviatūra',
                'pelytė',
                'monitorius'
            ],
            'kulinarija'=>[
                'maistas',
                'valgyti',
                'kalorijos',
                'sriuba',
                'lėkštė',
                'puodas',
                'gaminti',
                'virti',
                'kepti',
                'gaminti',
                'skanu',
                'apetitas'
            ],
            'buitis'=>[
                'televizorius',
                'indaplovė',
                'šaldytuvas',
                'atnešti',
                'nupirkti'
            ],
            'automobiliai'=>[
                'akumuliatorius',
                'variklis',
                'sėdynės',
                'užvesti',
                'starteris',
                'sankaba',
                'žibintai'
            ]
        ];
        $i = 3;
        $randomTags = array_rand($tags, $i);

        foreach($tags as $p=>$c){
            $parent = new Tags();
            $parent->setTitle($p);

            if( in_array($p, $randomTags) ){
                $this->addReference('tags'.$i, $parent);
                $i--;
            }

            $manager->persist($parent);
            foreach($c as $child){
                if(empty($child)){
                    continue;
                }
                $tag = new Tags();
                $tag->setTitle($child);
                $tag->setParent($parent);
                $manager->persist($tag);
            }
        }

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 2;
    }
}