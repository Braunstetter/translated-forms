<?php

namespace Braunstetter\TranslatedForms;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Braunstetter\TranslatedForms\DependencyInjection\TranslatedFormsExtension;

class TranslatedFormsBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new TranslatedFormsExtension();
    }
}