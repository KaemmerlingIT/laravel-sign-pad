<?php

namespace Kaemmerlingit\LaravelSignPad;

use Kaemmerlingit\LaravelSignPad\Commands\InstallCommand;
use Kaemmerlingit\LaravelSignPad\Components\SignaturePad;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelSignPadServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-sign-pad')
            ->hasConfigFile()
            ->hasViews('laravel-sign-pad')
            ->hasRoute('web')
            ->hasAssets()
            ->hasViewComponent('kaemmerlingit', SignaturePad::class)
            ->hasMigration('create_signatures_table')
            ->hasCommand(InstallCommand::class);
    }
}
