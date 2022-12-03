<?php

namespace Braunstetter\TranslatedForms\Tests\Functional\app;

use Braunstetter\TranslatedForms\TranslatedFormsBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Exception;
use Knp\DoctrineBehaviors\DoctrineBehaviorsBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Bundle\TwigBundle\TwigBundle;

final class TestKernel extends Kernel
{
    /**
     * @param string[] $configs
     */
    public function __construct(
        private array $configs = []
    )
    {
        parent::__construct('test', true);
    }

    /**
     * @return BundleInterface[]
     */
    public function registerBundles(): array
    {
        return [
            new FrameworkBundle(),
            new SecurityBundle(),
            new DoctrineBundle(),
            new DoctrineBehaviorsBundle(),
            new TranslatedFormsBundle(),
            new TwigBundle()
        ];
    }

    public function getCacheDir(): string
    {
        return sys_get_temp_dir() . '/bs_translated_forms_test';
    }

    public function getLogDir(): string
    {
        return sys_get_temp_dir() . '/bs_translated_forms_test_log';
    }


    /**
     * @throws Exception
     */
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/Resources/config/config.yaml');
        foreach ($this->configs as $config) {
            $loader->load($config);
        }
    }
}
