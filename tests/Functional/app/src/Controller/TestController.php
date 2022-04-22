<?php

namespace Braunstetter\TranslatedForms\Tests\Functional\app\src\Controller;

use Braunstetter\TranslatedForms\Tests\Fixtures\Entity\TranslatableEntity;
use Braunstetter\TranslatedForms\Tests\Functional\app\src\Form\TranslatedEntryForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\VarDumper\VarDumper;

class TestController extends AbstractController
{

    #[Route('/test', name: 'test')]
    public function test(Request $request)
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

        $form = $this->createForm(TranslatedEntryForm::class, $translatableEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->json([
                'title' => $translatableEntity->getTitle(),
                'untranslatedString' => $translatableEntity->getUntranslatedString()
            ]);
        }
//        VarDumper::dump($request);
        return $this->renderForm('test.html.twig', ['form' => $form]);
    }
}