<?php

namespace Tjall\Pagebuilder\Commands;

use Illuminate\Console\Command;
use Filament\Support\Commands\Concerns\CanManipulateFiles;
use Illuminate\Support\Str;

class MakePagebuilderBlock extends Command
{
    use CanManipulateFiles;

    protected $signature = 'make:pagebuilder-block {name}';
    protected $description = 'Create a new Pagebuilder block class and view';

    public function handle()
    {
        $name = $this->argument('name');
        $class = $this->argument('name').'Block';
        $slug = Str::slug(Str::snake($class));
        $label = Str::ucfirst(str_replace('_', ' ', Str::lower(Str::snake($name))));

        $blockClassPath = "app/Pagebuilder/Blocks/{$class}.php";
        $view = "pagebuilder.blocks.{$slug}";
        $namespace = "App\\Pagebuilder\\Blocks";

        // Copy stub to app using Filament's method
        $this->copyStubToApp(
            'PagebuilderBlock',
            $blockClassPath,
            [
                'namespace' => $namespace,
                'label' => $label,
                'class' => $class,
                'view' => $view,
            ]
        );

        // Create blade view using artisan callSilent with snake_case name
        $this->callSilent('make:view', ['name' => $view]);

        $this->info("Block class created: " . base_path($blockClassPath));
        $this->info("Block view created: " . resource_path("views/pagebuilder/blocks/{$slug}.blade.php"));
    }
}
