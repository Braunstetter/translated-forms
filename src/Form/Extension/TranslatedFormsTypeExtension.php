<?php

namespace Braunstetter\TranslatedForms\Form\Extension;

use Braunstetter\TranslatedForms\Form\DataMapper\TranslationsDataMapper;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslatedFormsTypeExtension extends AbstractTypeExtension
{
    private TranslationsDataMapper $translationsDataMapper;

    public function __construct(TranslationsDataMapper $translationsDataMapper)
    {
        $this->translationsDataMapper = $translationsDataMapper;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($builder->getOption('translated')) {
            $builder->setDataMapper($this->translationsDataMapper);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->define('translated')
            ->required()
            ->default(false)
            ->allowedTypes('bool')
            ->info('This option enables automatic translation mapping.');
    }

    /**
     * @inheritDoc
     */
    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }
}