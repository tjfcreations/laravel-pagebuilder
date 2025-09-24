<?php

namespace Tjall\Pagebuilder;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Tjall\Pagebuilder\Commands\MakePagebuilderBlock;
use Tjall\Pagebuilder\Commands\MakePagebuilderShortcode;

class PagebuilderServiceProvider extends PackageServiceProvider {
    public function configurePackage(Package $package): void {
        $package
            ->name('laravel-pagebuilder')
            ->discoversMigrations()
            ->hasCommands([
                MakePagebuilderBlock::class,
                MakePagebuilderShortcode::class
            ]);
    }

    public function bootingPackage() {
        $this->publishes([
            __DIR__.'/Filament/Resources' => app_path('Filament/Resources'),
        ], 'filament-resources');
    }

    public function packageBooted(): void {
        $this->app->register(DynamicPageServiceProvider::class);
    }
}
