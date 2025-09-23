<?php

namespace Tjall\Users\Filament\Resources\UserResource\Pages;

use Tjall\Users\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
