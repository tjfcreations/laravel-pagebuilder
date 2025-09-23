<?php

namespace Tjall\PageBuilder;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Tjall\Pagebuilder\Console\Commands\MakePagebuilderBlock;

class PagebuilderServiceProvider extends PackageServiceProvider {

    public function configurePackage(Package $package): void {
        $package
            ->name('laravel-pagebuilder');
    }

    public function boot() {
        $this->commands([
            MakePagebuilderBlock::class,
        ]);

        $this->publishes([
            __DIR__.'/Filament/Resources' => app_path('Filament/Resources'),
        ], 'filament-resources');
    }
}
