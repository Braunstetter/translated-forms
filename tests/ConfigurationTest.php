<?php

namespace Braunstetter\TranslatedForms\Tests;

use Braunstetter\TranslatedForms\DependencyInjection\TranslatedFormsExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionConfigurationTestCase;
use Braunstetter\TranslatedForms\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

class ConfigurationTest extends AbstractExtensionConfigurationTestCase
{
    protected function getContainerExtension(): ExtensionInterface
    {
        return new TranslatedFormsExtension();
    }

    protected function getConfiguration(): ConfigurationInterface
    {
        return new Configuration();
    }

    public function test_configuration_has_tree()
    {
        $this->assertProcessedConfigurationEquals([], [
            __DIR__ . '/Functional/app/Resources/config/translated_forms.yaml'
        ]);
    }

}
