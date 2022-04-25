# Translated (Symfony) Forms

This bundle gives you a `translated` option for your Symfony forms. So when you switch languages your forms are translated and work just fine. 

It is assumed that you are using the [KNP Doctrine Behaviours](https://github.com/KnpLabs/DoctrineBehaviors/blob/master/docs/translatable.md) for your translations.

## Installation

`composer require braunstetter/translated-forms`

## Usage

Once installed this bundle lets you set an option inside your form parameter array: 

```php
public function configureOptions(OptionsResolver $resolver): void
{
    $resolver->setDefaults([
        'translated' => true
    ]);
}
```

When this is set to true your form will react to the current locale inside your request object.
> It is important to use [Proxy Translations](https://github.com/KnpLabs/DoctrineBehaviors/blob/master/docs/translatable.md#proxy-translations) to allow this bundle to read data by a magic method.

## How does it work?

### Reading the data (mapDataToForms)
If enabled this bundle sets a new data mapper to your forms. The default data mapper of the Symfony form component does not work with magic properties - the data mapper of this bundle does. 

### Writing the data (mapFormsToData)
When data of a form is going to be read - this data mapper tries to save it the normal way. When the field of the base entity is not writeable directly it is going to pick the current translation and is writing it into it.   

### Why should you use this bundle?
There is a popular bundle [a2lix/translation-form-bundle](https://github.com/a2lix/TranslationFormBundle). 
It is actively maintained and if you like it - you can stick to it. 
But to use `a2lix/translation-form-bundle` there is some CSS and Javascript required. `braunstetter/translated-forms` will just work fine with all your Symfony forms. No extra CSS no Javascript. Just switch the language and even nested forms are translated.