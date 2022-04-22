<?php

namespace Braunstetter\TranslatedForms\Tests\Functional\app\src;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;

class FormProvider
{
    private FormFactory $factory;

    public function __construct(FormFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function get(): FormFactory
    {
        return $this->factory;
    }
}