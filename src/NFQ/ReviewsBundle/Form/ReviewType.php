<?php

namespace NFQ\ReviewsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReviewType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('thank', 'checkbox', ['required' => false, 'label' => 'review.checkbox_label'])
            ->add('reviewMessage', 'textarea', ['required' => false, 'label' => 'review.message_label'])
            ->add('save', 'submit', array('label' => 'review.submit_button', 'translation_domain' => 'messages'));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NFQ\ReviewsBundle\Entity\Review'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'nfq_reviewsbundle_review';
    }
}
