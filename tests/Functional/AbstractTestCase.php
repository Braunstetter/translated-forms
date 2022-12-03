<?php

namespace Braunstetter\TranslatedForms\Tests\Functional;

use Braunstetter\TranslatedForms\Tests\Functional\app\TestKernel;
use Doctrine\DBAL\Logging\DebugStack;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\ToolsException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class AbstractTestCase extends TestCase
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    private ContainerInterface $container;
    protected KernelInterface $kernel;

    /**
     * @throws ToolsException
     */
    protected function setUp(): void
    {
        $this->kernel = new TestKernel($this->provideCustomConfigs());
        $this->kernel->boot();

        $this->container = $this->kernel->getContainer();

        $this->entityManager = $this->getService('doctrine.orm.entity_manager');

        $this->loadDatabaseFixtures();
    }

    /**
     * @throws ToolsException
     */
    protected function loadDatabaseFixtures(): void
    {
        /** @var DatabaseLoader $databaseLoader */
        $databaseLoader = $this->getService(DatabaseLoader::class);
        $databaseLoader->reload();
    }

    /**
     * @return string[]
     */
    protected function provideCustomConfigs(): array
    {
        return [];
    }

    protected function createAndRegisterDebugStack(): DebugStack
    {
        $debugStack = new DebugStack();

        $this->entityManager->getConnection()
            ->getConfiguration()
            ->setSQLLogger($debugStack);

        return $debugStack;
    }

    /**
     * @template T as object
     * @param class-string<T> $type
     * @return T
     */
    protected function getService(string $type): object
    {
        return $this->container->get($type);
    }

}