<?php

declare(strict_types=1);

namespace Braunstetter\TranslatedForms\Entity\Traits;

use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait as KnpTranslatableTrait;

trait TranslatableTrait
{
    use KnpTranslatableTrait;

    public static function getTranslationEntityClass(): string
    {
        $explodedNamespace = explode('\\', static::class);
        $entityClass = array_pop($explodedNamespace);

        return '\\' . implode('\\', $explodedNamespace) . '\\Translation\\' . $entityClass . 'Translation';
    }

    public function __call($method, $arguments)
    {
        return $this->proxyCurrentLocaleTranslation($method, $arguments);
    }
}