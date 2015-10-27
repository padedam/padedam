<?php
/**
 * Created by PhpStorm.
 * User: Matas
 * Date: 2015.10.24
 * Time: 22:23
 */
namespace NFQ\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('username');
        $builder->add('first_name', 'text',
            array(
                "label" => "user.label_name",
                "required" => true,
            )
        );
        $builder->add('last_name', 'text',
            array(
                "label" => "user.label_surname",
                "required" => true,
            )
        );
        $builder->add('phone', 'text',
            array(
                "label" => "user.label.phone",
                "required" => true,
            )
        );
        $builder->add('birthday', 'date',
            array(
                "label" => "user.birth_year",
                "required" => true,
            )
        );
        $builder->add('description', 'textarea',
            array(
                'label' => "user.about_me",
                'required' => true,
                'attr' => [
                    'data-help' => 'user.short_about_me_desc',
                ]
            )
        );
        /*$builder->add('field', 'text', ['label' => 'user.short_about_me_desc']);*/
    }

    public function getParent()
    {
        return 'fos_user_registration';
    }

    public function getName()
    {
        return 'nfq_user_registration';
    }
}