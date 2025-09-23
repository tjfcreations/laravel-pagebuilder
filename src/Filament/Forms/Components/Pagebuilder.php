<?php
    namespace Tjall\Pagebuilder\Filament\Forms\Components;

    use Filament\Forms\Components\Builder;
    use Tjall\Pagebuilder\Support\Block;
    use Filament\Forms\Form;

    class Pagebuilder extends Builder {
        protected function setUp(): void {
            parent::setUp();

            $this
                ->hiddenLabel()
                ->addActionLabel('Blok toevoegen')
                ->addActionAlignment('left')
                ->blocks(function() {
                    $builderBlocks = [];

                    foreach(Block::all() as $block) {
                        $builderBlocks[] = Builder\Block::make($block->getType())
                            ->label($block->getLabel())
                            ->icon($block->getIcon())
                            ->schema(function() use ($block) {
                                return $block->getBuilderSchema();
                            });
                    }

                    return $builderBlocks;
                });
        }
    }