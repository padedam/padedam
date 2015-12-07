<?php

namespace NFQ\AssistanceBundle\Form;

use NFQ\AssistanceBundle\Entity\Tags;
use NFQ\AssistanceBundle\Repository\TagsRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssistanceRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('shortDescription', 'text', array("label" => "assistance.label_short_description",
                'attr' => array('placeholder' => '{{ "assistance.label_short_description"|trans }}'), 'translation_domain' => 'messages'))
            ->add('longDescription', 'textarea', array("label" => "assistance.label_long_description", 'translation_domain' => 'messages'))
            ->add('tags', 'entity', array(
                'class' => 'NFQ\AssistanceBundle\Entity\Tags',
                'query_builder' => function (TagsRepository $tr) {
                    return $tr->createQueryBuilder('t')
                        ->where('t.parent IS NULL')
                        ->orderBy('t.title', 'ASC');
                },
                'multiple' => true
            ))
            ->add('save', 'submit', array('label' => 'assistance.submit_button', 'translation_domain' => 'messages'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getName()
    {
        return 'nfqassistance_bundle_assistance_request_type';
    }
}
