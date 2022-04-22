<?php

namespace Braunstetter\TranslatedForms\Tests\Functional;


use Braunstetter\TranslatedForms\Tests\Fixtures\Entity\TranslatableEntity;
use Braunstetter\TranslatedForms\Tests\Functional\app\src\Form\TranslatedEntryForm;
use Braunstetter\TranslatedForms\Tests\Functional\app\src\FormProvider;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\Util\InheritDataAwareIterator;
use Symfony\Component\HttpKernel\HttpKernelBrowser;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\VarDumper\VarDumper;

class TranslatedFormsTest extends AbstractTestCase
{

    /**
     * @var ObjectRepository<TranslatableEntity>
     */
    private ObjectRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->entityManager->getRepository(TranslatableEntity::class);
    }

    public function test_form_errors_when_not_translated(): void
    {
        $translatableEntity = new TranslatableEntity();
        $translatableEntity->setUntranslatedString('this is untranslated');
        $translatableEntity->translate('fr')
            ->setTitle('fabuleux');
        $translatableEntity->translate('en')
            ->setTitle('awesome');
        $translatableEntity->translate('ru')
            ->setTitle('удивительный');
        $translatableEntity->mergeNewTranslations();

        $factory = $this->getService(FormProvider::class);

        $this->expectException(NoSuchPropertyException::class);
        $factory->get()->create(TranslatedEntryForm::class, $translatableEntity, ['translated' => false]);
    }

    public function test_form_errors_disappears_when_translated(): void
    {
        $translatableEntity = new TranslatableEntity();
        $translatableEntity->setUntranslatedString('this is untranslated');
        $translatableEntity->translate('fr')
            ->setTitle('fabuleux');
        $translatableEntity->translate('en')
            ->setTitle('awesome');
        $translatableEntity->translate('ru')
            ->setTitle('удивительный');
        $translatableEntity->mergeNewTranslations();

        $factory = $this->getService(FormProvider::class);
        $factory->get()->create(TranslatedEntryForm::class, $translatableEntity);

        $this->assertTrue(true);
    }

    public function test_new()
    {
        $client = new KernelBrowser($this->kernel);
        $client->request('GET', '/test');

        $contentArray = [
            'translated_entry_form' => [
                "submit" => "",
                "untranslatedString" => "this is changed",
                "title" => "awesome nice",
            ]
        ];

        $client->submitForm('Submit', $contentArray);

        $data = json_decode($client->getResponse()->getContent());

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame($contentArray, $client->getRequest()->request->all());
        $this->assertSame('this is changed', $data->untranslatedString);
        $this->assertSame('awesome nice', $data->title);

    }

    public function test_mapper_returns_null_if_view_data_is_empty()
    {
        $translatableEntity = new TranslatableEntity();
        $translatableEntity->setUntranslatedString('this is untranslated');
        $translatableEntity->translate('fr')
            ->setTitle('fabuleux');
        $translatableEntity->translate('en')
            ->setTitle('awesome');
        $translatableEntity->translate('ru')
            ->setTitle('удивительный');
        $translatableEntity->mergeNewTranslations();

        $factory = $this->getService(FormProvider::class);
        $form = $factory->get()->create(TranslatedEntryForm::class, $translatableEntity);

        $viewData = null;

        $this->assertSame($form->getConfig()->getDataMapper()->mapFormsToData(
            new \RecursiveIteratorIterator(new InheritDataAwareIterator(new \ArrayIterator($form->all()))),
            $viewData
        ),
            null
        );
    }

    public function test_mapper_returns_exception_if_view_data_is_string()
    {
        $translatableEntity = new TranslatableEntity();
        $translatableEntity->setUntranslatedString('this is untranslated');
        $translatableEntity->translate('fr')
            ->setTitle('fabuleux');
        $translatableEntity->translate('en')
            ->setTitle('awesome');
        $translatableEntity->translate('ru')
            ->setTitle('удивительный');
        $translatableEntity->mergeNewTranslations();

        $factory = $this->getService(FormProvider::class);
        $form = $factory->get()->create(TranslatedEntryForm::class, $translatableEntity);

        $this->expectException(UnexpectedTypeException::class);
        $viewData = '';
        $form->getConfig()->getDataMapper()->mapFormsToData(
            new \RecursiveIteratorIterator(new InheritDataAwareIterator(new \ArrayIterator($form->all()))),
            $viewData
        );
    }
}