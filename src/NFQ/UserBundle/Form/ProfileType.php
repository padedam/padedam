<?php
/**
 * Created by PhpStorm.
 * User: Matas
 * Date: 2015.10.25
 * Time: 14:37
 */

namespace NFQ\UserBundle\Form;

use NFQ\AssistanceBundle\Repository\TagsRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('username');
        $builder->add('first_name');
        $builder->add('last_name');
        $builder->add('phone');
        $builder->add('birthday', 'birthday');
        $builder->add('description');
        $builder->add('taglist', 'entity', array(
            'class' => 'NFQ\AssistanceBundle\Entity\Tags',
            'query_builder' => function(TagsRepository $er) {
                return $er->createQueryBuilder('tags')
                    ->orderBy('tags.title', 'ASC');
            },
        ));
    }

    public function getParent()
    {
        return 'fos_user_profile';
    }

    public function getName()
    {
        return 'nfq_user_profile';
    }
}