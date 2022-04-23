<?php

namespace Braunstetter\TranslatedForms\Tests\Functional;


use Braunstetter\TranslatedForms\Tests\Fixtures\Entity\TranslatableEntity;
use Braunstetter\TranslatedForms\Tests\Functional\app\src\Form\TranslatedEntryForm;
use Braunstetter\TranslatedForms\Tests\Functional\app\src\FormProvider;
use Braunstetter\TranslatedForms\Tests\Functional\app\src\Service\FixtureProvider;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Util\InheritDataAwareIterator;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;

class TranslatedFormsTest extends AbstractTestCase
{

    private TranslatableEntity $translatedEntity;
    private mixed $formFactory;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->translatedEntity = FixtureProvider::get();
        $this->formFactory = $this->getService(FormProvider::class);
        $this->client = new KernelBrowser($this->kernel);
    }

    public function test_form_errors_on_init_when_not_translated(): void
    {
        $this->expectException(NoSuchPropertyException::class);
        $this->formFactory->get()->create(TranslatedEntryForm::class, $this->translatedEntity, ['translated' => false]);
    }

    public function test_form_errors_on_init_disappears_when_translated(): void
    {
        $this->formFactory->get()->create(TranslatedEntryForm::class, $this->translatedEntity);
        $this->assertTrue(true);
    }

    /**
     * @dataProvider provideFormContent
     */
    public function test_request_contains_correct_values($content)
    {
        $this->client->request('GET', '/test');
        $this->client->submitForm('Submit', $content);
        $this->assertSame($content, $this->client->getRequest()->request->all());
    }

    /**
     * @dataProvider provideFormContent
     */
    public function test_changes_made_to_form_gets_mapped_correctly($content)
    {
        $this->client->request('GET', '/test');
        $this->client->submitForm('Submit', $content);
        $data = json_decode($this->client->getResponse()->getContent());

        $this->assertSame('this is changed', $data->untranslatedString);
        $this->assertSame('awesome nice', $data->title);
    }

    /**
     * @dataProvider provideFormContent
     */
    public function test_form_submission_works($content)
    {
        $this->client->request('GET', '/test');
        $this->client->submitForm('Submit', $content);

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    public function test_mapper_returns_null_if_view_data_is_empty()
    {
        $form = $this->formFactory->get()->create(TranslatedEntryForm::class, $this->translatedEntity);
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
        $form = $this->formFactory->get()->create(TranslatedEntryForm::class, $this->translatedEntity);
        $viewData = '';

        $this->expectException(UnexpectedTypeException::class);

        $form->getConfig()->getDataMapper()->mapFormsToData(
            new \RecursiveIteratorIterator(new InheritDataAwareIterator(new \ArrayIterator($form->all()))),
            $viewData
        );
    }

    public function provideFormContent(): array
    {
        return [
            [
                ['translated_entry_form' => ["submit" => "", "untranslatedString" => "this is changed", "title" => "awesome nice",]]
            ]
        ];
    }
}