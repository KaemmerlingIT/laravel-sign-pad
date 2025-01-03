# Laravel pad signature

A Laravel package to save (multiple) signatures associated to a Eloquent model.

This library is based on [creagia/laravel-sign-pad](https://github.com/creagia/laravel-sign-pad) and was enhanced with
several features like support for multiple signatures on a single model or encrypting the stored signature files.
Support for 

[![Latest Version on Packagist](https://img.shields.io/packagist/v/Kaemmerlingit/laravel-sign-pad.svg?style=flat-square)](https://packagist.org/packages/Kaemmerlingit/laravel-sign-pad)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/Kaemmerlingit/laravel-sign-pad/run-tests.yml?label=tests)](https://github.com/Kaemmerlingit/laravel-sign-pad/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/Kaemmerlingit/laravel-sign-pad.svg?style=flat-square)](https://packagist.org/packages/Kaemmerlingit/laravel-sign-pad)


## Requirements

Laravel pad signature requires **PHP 8.0, 8.1, 8.2 or 8.3** and **Laravel 8, 9, 10 or 11**.

## Installation

You can install the package via composer:

```bash
composer require Kaemmerlingit/laravel-sign-pad
```

Publish the config and the migration files and migrate the database

```bash
php artisan sign-pad:install
```

Publish the .js assets:

```bash
php artisan vendor:publish --tag=sign-pad-assets
```

This will copy the package assets inside the `public/vendor/sign-pad/` folder.

## Configuration

In the published config file `config/sign-pad.php` you'll be able to configure many important aspects of the package, 
like the route name where users will be redirected after signing the document or where do you want to store the signed documents.
You can customize the disk and route to store signatures and documents.

Notice that the redirect_route_name will receive the parameter `$uuid` with the uuid of the signature model in the database. 
If no `redirect_route_name` route is defined, it will return a HTTP 201 code without any further content.

## Preparing your model

Add the `RequiresSignature` trait and implement the `CanBeSigned` class to the model you would like.

```php
<?php

namespace App\Models;

use Kaemmerlingit\LaravelSignPad\Concerns\RequiresSignature;
use Kaemmerlingit\LaravelSignPad\Contracts\CanBeSigned;

class MyModel extends Model implements CanBeSigned
{
    use RequiresSignature;

}

?>
```
A `$model` object will be automatically injected into the Blade template, so you will be able to access all the needed properties of the model.

## Usage

At this point, all you need is to create the form with the sign pad canvas in your template. For the route of the form, you have to call the method getSignatureRoute() from the instance of the model you prepared before:

```html
@if (!$myModel->hasBeenSigned())
    <form action="{{ $myModel->getSignatureRoute() }}" method="POST">
        @csrf
        <div style="text-align: center">
            <x-Kaemmerlingit-signature-pad />
        </div>
    </form>
    <script src="{{ asset('vendor/sign-pad/sign-pad.min.js') }}"></script>
@endif
```

### Retrieving signatures

You can retrieve your model signature using the Eloquent relation `$myModel->signature`. After that,
you can use:
- `getSignatureImagePath()` returns the signature image path.
- `getSignatureImageAbsolutePath()` returns the signature image absolute path.
- `getSignatureImageContent()` returns the content of the image path, 

```php
echo $myModel->signature->getSignatureImagePath();
```

### Deleting signatures

You can delete your model signature using
- `deleteSignature()` method in the model.
```php
echo $myModel->deleteSignature();
```

## Customizing the component

From the same template, you can change the look of the component by passing some properties:
- *border-color* (hex) to change the border color of the canvas
- *pad-classes* and *button-classes* (strings) indicates which classes will have the sign area or the submit & clear buttons
- *clear-name* and *submit-name* (strings) allows you to modify de default "Submit" and "Clear" values of the buttons.
- *disabled-without-signature* (boolean) indicates if the submit button should be disabled when the user has not signed yet.
- *part* (string) indicates the part of the signature and is passed to the model.

An example with an app using Tailwind would be:

```html
  <x-Kaemmerlingit-signature-pad
      border-color="#eaeaea"
      pad-classes="rounded-xl border-2"
      button-classes="bg-gray-100 px-4 py-2 rounded-xl mt-4"
      clear-name="Clear"
      submit-name="Submit"
      :disabled-without-signature="true"
  />
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
