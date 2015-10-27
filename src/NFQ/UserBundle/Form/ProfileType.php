<?php
/**
 * Created by PhpStorm.
 * User: Matas
 * Date: 2015.10.25
 * Time: 14:37
 */

namespace NFQ\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
class ProfileType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('username');
        $builder->add('first_name', 'user.name', [ 'attr'] => ['class' => 'form-control']);
        $builder->add('last_name');
        $builder->add('phone');
        $builder->add('birthday');
        $builder->add('description');
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