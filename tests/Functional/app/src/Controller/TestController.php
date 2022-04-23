<?php

namespace Braunstetter\TranslatedForms\Tests\Functional\app\src\Controller;

use Braunstetter\TranslatedForms\Tests\Functional\app\src\Form\TranslatedEntryForm;
use Braunstetter\TranslatedForms\Tests\Functional\app\src\Service\FixtureProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{

    #[Route('/test', name: 'test')]
    public function test(Request $request): JsonResponse|Response
    {
        $translatableEntity = FixtureProvider::get();

        $form = $this->createForm(TranslatedEntryForm::class, $translatableEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->json([
                'title' => $translatableEntity->getTitle(),
                'untranslatedString' => $translatableEntity->getUntranslatedString()
            ]);
        }

        return $this->renderForm('test.html.twig', ['form' => $form]);
    }
}