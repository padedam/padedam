<?php
/**
 * Created by PhpStorm.
 * User: Matas
 * Date: 2015.12.05
 * Time: 21:54
 */

namespace NFQ\AssistanceBundle\Form;


use NFQ\AssistanceBundle\Entity\AssistanceRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RequestStatusType extends AbstractType{

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => array(
                AssistanceRequest::STATUS_WAITING => 'WAITING',
                AssistanceRequest::STATUS_TAKEN => 'TAKEN',
                AssistanceRequest::STATUS_DONE => 'DONE',
                AssistanceRequest::STATUS_CANCELED => 'CANCELED',
            )
        ));
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}