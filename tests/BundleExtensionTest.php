<?php

namespace Braunstetter\TranslatedForms\Tests;

use Braunstetter\TranslatedForms\DependencyInjection\TranslatedFormsExtension;
use Braunstetter\TranslatedForms\Form\Extension\TranslatedFormsTypeExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

class BundleExtensionTest extends AbstractExtensionTestCase
{
    protected function getContainerExtensions(): array
    {
        return [new TranslatedFormsExtension()];
    }

    public function test_twig_extension_gets_loaded()
    {
        $this->load();
        $this->assertContainerBuilderHasService(TranslatedFormsTypeExtension::class);
    }

}