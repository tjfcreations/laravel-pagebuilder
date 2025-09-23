<?php

namespace Tjall\Users;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Tjall\Users\Filament\Resources\UserResource;

class PagebuilderPlugin implements Plugin {
    public function getId(): string {
        return 'tjall/laravel-pagebuilder';
    }

    public function register(Panel $panel): void {
        $panel
            ->resources([
                PageBuilder::class
            ]);
    }

    public function boot(Panel $panel): void {
        //
    }
}
