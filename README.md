# Translated (Symfony) Forms

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Braunstetter/translated-forms/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/Braunstetter/menu-bundle/?branch=main)
[![Build Status](https://app.travis-ci.com/Braunstetter/translated-forms.svg?branch=main)](https://app.travis-ci.com/Braunstetter/menu-bundle)
[![Total Downloads](http://poser.pugx.org/braunstetter/translated-forms/downloads)](https://packagist.org/packages/braunstetter/translated-forms)
[![License](http://poser.pugx.org/braunstetter/translated-forms/license)](https://packagist.org/packages/braunstetter/translated-forms)

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

## Why should you use this bundle?
There is the popular [a2lix/translation-form-bundle](https://github.com/a2lix/TranslationFormBundle).
It is actively maintained and if you like it - you can stick to it.  

But to use `a2lix/translation-form-bundle` there is some CSS and Javascript required. Also, it comes with a dedicated FormType. So you have to wrap every field inside this FormType. It provides these clickable tabs for every single translated field, which is not so handy, when you just want to translate your whole form.

`braunstetter/translated-forms` will just work fine with all your Symfony forms. No extra CSS no Javascript no extra FormType to implement. Your forms stay the same. Just switch the languages and even nested forms are translated.

## Custom Traits
Knp Doctrine Behaviours brings some Traits to your translatable Entities and your translations. 

By default, it assumes you put the translation Entity into the same folder as the translatable. This gets quite messy, as soon as you translate a lot.  

So `braunstetter/translated-forms` bundle delivers two traits. [TranslatableTrait](src/Entity/Trait/TranslatableTrait.php) and [TranslationTrait](src/Entity/Trait/TranslationTrait.php). 

They work just the same way as the default one's form KNP (in fact they use it under the hood), but they put translations into a `Translation` folder - and they implement the magic `__call` method which is required for this bundle to translate your forms.

## How does this magic happen?

### Reading the data (mapDataToForms)
If enabled this bundle sets a new data mapper to your forms.  

The default data mapper of the Symfony form component does not work with magic properties - the data mapper of this bundle does. 

### Writing the data (mapFormsToData)
When data of a form is going to be saved - this data mapper tries to save it the normal way. 

If the field of the base entity is not writeable directly it is going to pick the current translation and is writing it into it.   