<?php

namespace NFQ\AssistanceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssistanceRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('assistanceField','choice', array("choices"=>array(0=>'IT',1=>'Dujotiekis',2=>'Santechnika',3=>'Elektronika'),
                'attr'=> array('class'=>'col-lg-3 control-label','for'=>'assistanceField'), 'translation_domain' => 'messages'))
            ->add('shortDescription', 'text', array("label"=>"assistance.label_short_description",
                'attr'=>array('placeholder'=>'{{ "assistance.label_short_description"|trans }}'), 'translation_domain' => 'messages'))
            ->add('longDescription', 'textarea', array("label"=>"assistance.label_long_description", 'translation_domain' => 'messages'))
            ->add('save','submit', array('label'=>'assistance.submit_button', 'translation_domain' => 'messages'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getName()
    {
        return 'nfqassistance_bundle_assistance_request_type';
    }
}
