<?php
namespace Tjall\Pagebuilder\Enums;

use Filament\Support\Contracts\HasLabel;

enum PageTypeEnum: string implements HasLabel
{
    case Static = 'static';
    case Template = 'template';
    case Index = 'index';

    public function getLabel(): string {
        return match($this) {
            self::Static => 'Standaard',
            self::Index => 'Overzicht',
            self::Template => 'Template',
        };
    }
}
