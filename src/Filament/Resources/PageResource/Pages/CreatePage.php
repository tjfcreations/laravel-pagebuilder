<?php

namespace Tjall\Pagebuilder\Filament\Resources\PageResource\Pages;

use Tjall\Pagebuilder\Filament\Resources\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePage extends CreateRecord
{
    protected static string $resource = PageResource::class;
}
