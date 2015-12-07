<?php
/**
 * Created by PhpStorm.
 * User: Matas
 * Date: 2015.10.25
 * Time: 14:37
 */

namespace NFQ\UserBundle\Form;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('username');
        $builder->add('first_name', 'text', [
            'label' => 'user.name'
        ]);
        $builder->add('last_name', 'text', [
            'label' => 'user.surname'
        ]);
        $builder->add('phone', 'text', [
            'label' => 'user.phone'
        ]);
        $builder->add('birthday', 'birthday', [
            'label' => 'user.birth_year'
        ]);
        $builder->add('description', 'textarea',
            [
                'label' => "user.about_me",
                'required' => true,
                'attr' => [
                    'data-help' => 'user.short_about_me_desc',
                ]
            ]
        );
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