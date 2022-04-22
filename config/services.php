<?php

declare(strict_types=1);

use Braunstetter\TranslatedForms\Form\DataMapper\TranslationsDataMapper;
use Braunstetter\TranslatedForms\Form\Extension\TranslatedFormsTypeExtension;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {

    $services = $containerConfigurator->services();

    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();

    $services->load('Braunstetter\TranslatedForms\\', __DIR__ . '/../src')
        ->exclude([
            __DIR__ . '/../src/TranslatedFormsBundle.php',
        ]);

    $services
        ->set(TranslatedFormsTypeExtension::class)
        ->arg('$translationsDataMapper', service(TranslationsDataMapper::class))
        ->tag('form.type_extension');

};
