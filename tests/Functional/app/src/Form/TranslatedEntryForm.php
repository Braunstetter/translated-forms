<?php

namespace Braunstetter\TranslatedForms\Tests\Functional\app\src\Form;

use Braunstetter\TranslatedForms\Tests\Fixtures\Entity\TranslatableEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslatedEntryForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('untranslatedString')
            ->add('title')
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TranslatableEntity::class,
            'translated' => true
        ]);
    }
}