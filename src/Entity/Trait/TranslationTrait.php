<?php

declare(strict_types=1);

namespace Braunstetter\TranslatedForms\Entity\Trait;

use Knp\DoctrineBehaviors\Model\Translatable\TranslationTrait as KnpTranslationTrait;

trait TranslationTrait
{
    use KnpTranslationTrait;

    public static function getTranslatableEntityClass(): string
    {
        $explodedNamespace = explode('\\', __CLASS__);
        $entityClass = array_pop($explodedNamespace);
        // Remove Translation namespace
        array_pop($explodedNamespace);

        return '\\' . implode('\\', $explodedNamespace) . '\\' . substr($entityClass, 0, -11);
    }
}