<?php

namespace Tjall\Pagebuilder\Console\Commands;

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
        $blockNamespace = "App\\Pagebuilder\\Blocks";
        $blockClassPath = "app/Pagebuilder/Blocks/{$name}.php";
        $stubPath = base_path('src/stubs/pagebuilder-block.stub');

        // Copy stub to app using Filament's method
        $this->copyStubToApp(
            $stubPath,
            $blockClassPath,
            [
                'namespace' => $blockNamespace,
                'class' => $name,
            ]
        );

        // Create blade view using artisan callSilent with snake_case name
        $snakeName = Str::snake($name);
        $this->callSilent('make:view', [
            'name' => "pagebuilder.blocks.{$snakeName}",
        ]);

        $this->info("Block class created: " . base_path($blockClassPath));
        $this->info("Block view created: " . resource_path("views/pagebuilder/blocks/{$snakeName}.blade.php"));
    }
}
