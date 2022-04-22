<?php

namespace Braunstetter\TranslatedForms\Form\DataMapper;

use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\DataAccessor\CallbackAccessor;
use Symfony\Component\Form\Extension\Core\DataAccessor\ChainAccessor;
use Symfony\Component\Form\Extension\Core\DataAccessor\PropertyPathAccessor;
use Symfony\Component\Form\Extension\Core\DataMapper\DataMapper;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\VarDumper\VarDumper;
use Traversable;
use function is_array;
use function is_object;

class TranslationsDataMapper implements DataMapperInterface
{

    private ChainAccessor $formDataAccessor;
    private RequestStack $requestStack;
    private DataMapper $magicDataMapper;
    private PropertyAccessor $propertyAccessor;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;

        $this->formDataAccessor = new ChainAccessor([
            new CallbackAccessor(),
            new PropertyPathAccessor(PropertyAccess::createPropertyAccessorBuilder()->disableMagicCall()->getPropertyAccessor()),
        ]);

        $this->magicDataMapper = new DataMapper(new ChainAccessor([
            new CallbackAccessor(),
            new PropertyPathAccessor(PropertyAccess::createPropertyAccessorBuilder()->enableMagicCall()->getPropertyAccessor()),
        ]));

        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }


    /**
     * {@inheritdoc}
     */
    public function mapDataToForms(mixed $viewData, \Traversable $forms): void
    {
        $this->magicDataMapper->mapDataToForms($viewData, $forms);
    }

    /**
     * @inheritDoc
     */
    public function mapFormsToData(Traversable $forms, mixed &$viewData)
    {

        if (null === $viewData) {
            return;
        }

        if (!is_array($viewData) && !is_object($viewData)) {
            throw new UnexpectedTypeException($viewData, 'object, array or empty');
        }

        /** @var FormInterface $form */
        foreach ($forms as $form) {

            $config = $form->getConfig();

            // Just the normal native DataMapper process after checking
            // if the value is writeable on the underlying object/array.
            if ($this->propertyAccessor->isWritable($viewData, $form->getName()) && $config->getMapped()) {

                // Write-back is disabled if the form is not synchronized (transformation failed),
                // if the form was not submitted and if the form is disabled (modification not allowed)
                if ($form->isSubmitted() && $form->isSynchronized() && !$form->isDisabled() && $this->formDataAccessor->isWritable($viewData, $form)) {
                    $this->formDataAccessor->setValue($viewData, $form->getData(), $form);
                }

            }

            //  The data is mapped but can not be read from the object
            //  try to map it to the translation
            if (!$this->propertyAccessor->isWritable($viewData, $form->getName()) && $config->getMapped()) {
                $currentLocale = $this->requestStack->getCurrentRequest()->getLocale();

                if ($viewData instanceof TranslatableInterface && $config->getMapped() && $form->isSubmitted() && $form->isSynchronized() && !$form->isDisabled()) {
                    $translation = $viewData->translate($currentLocale, false);

                    if ($this->formDataAccessor->isWritable($translation, $form)) {
                        $this->formDataAccessor->setValue($translation, $form->getData(), $form);
                        $viewData->mergeNewTranslations();
                    }
                }
            }
        }

    }
}