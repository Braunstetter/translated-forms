<?php

namespace Braunstetter\TranslatedForms\Tests\Fixtures\Entity;

use Braunstetter\TranslatedForms\Entity\Traits\TranslatableTrait;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;

/**
 * @method string|null getTitle()
 * @method void setTitle(string $title)
 */
#[Entity]
class TranslatableEntity implements TranslatableInterface
{
    use TranslatableTrait;

    #[Id]
    #[Column(type: 'integer')]
    #[GeneratedValue(strategy: 'AUTO')]
    private int $id;

    public function getId(): int
    {
        return $this->id;
    }

    #[Column(type: 'string', length: 180, unique: true)]
    private string $untranslatedString;

    public function getUntranslatedString(): string
    {
        return $this->untranslatedString;
    }

    public function setUntranslatedString(string $untranslatedString): void
    {
        $this->untranslatedString = $untranslatedString;
    }
}