<?php

namespace Braunstetter\TranslatedForms\Tests;

use Nyholm\BundleTest\AppKernel;
use Braunstetter\TranslatedForms\DependencyInjection\TranslatedFormsExtension;
use Braunstetter\TranslatedForms\TranslatedFormsBundle;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class BundleTest extends KernelTestCase
{
    protected static function getKernelClass(): string
    {
        return AppKernel::class;
    }

    protected static function createKernel(array $options = []): KernelInterface
    {
        /**
         * @var AppKernel $kernel
         */
        $kernel = parent::createKernel($options);
        $kernel->addBundle(TranslatedFormsBundle::class);

        return $kernel;
    }

    public function testInitBundle(): void
    {
        self::bootKernel();
        $bundle = self::$kernel->getBundle('TranslatedFormsBundle');
        $this->assertInstanceOf(TranslatedFormsBundle::class, $bundle);
        $this->assertInstanceOf(TranslatedFormsExtension::class, $bundle->getContainerExtension());
    }

}