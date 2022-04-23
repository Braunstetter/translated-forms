<?php

namespace Braunstetter\TranslatedForms\Tests\Functional\app\src\Service;

use Braunstetter\TranslatedForms\Tests\Fixtures\Entity\TranslatableEntity;

class FixtureProvider
{
    public static function get(): TranslatableEntity
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
        return $translatableEntity;
    }
}