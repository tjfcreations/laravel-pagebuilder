<?php

namespace Tjall\Pagebuilder;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Tjall\Pagebuilder\Filament\Resources\PageResource;
use Tjall\Pagebuilder\Filament\Resources\TranslationResource;

class PagebuilderPlugin implements Plugin {
    public function getId(): string {
        return 'tjall/laravel-pagebuilder';
    }

    public function register(Panel $panel): void {
        $panel
            ->resources([
                PageResource::class,
                TranslationResource::class
            ]);
    }

    public function boot(Panel $panel): void {
        //
    }
}
