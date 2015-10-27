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
class RegistrationType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('username');
        $builder->add('first_name');
        $builder->add('last_name');
        $builder->add('phone');
        $builder->add('birthday');
        $builder->add('description');
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