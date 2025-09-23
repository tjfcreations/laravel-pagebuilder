<?php
    namespace Tjall\Pagebuilder\Support;

    use Illuminate\Support\Collection;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Blade;

    abstract class Block {
        public static string $view;
        public static string $label;
        public static ?string $icon = null;

        /**
         * @return Collection<int, Block>
         */
        public static function all(): Collection {
            return collect(config('pagebuilder.blocks', []))
                ->filter(fn($class) => is_subclass_of($class, Block::class))
                ->map(fn($class) => new $class());
        }

        public function render(array $data = []): string {
            $data = $this->with($data);

            return Blade::render(static::$view, $data);
        }

        public function with(array $data): array {
            $quickSelect = $this->quickSelect();
            
            if($quickSelect) {
                $data['records'] = $quickSelect->getRecords($data);
            }

            return $data;
        }

        public function getType(): string {
            return Str::snake(class_basename(static::class));
        }
        
        public function quickSelect(): ?QuickSelect {
            return null;
        }

        public function schema(): array {
            return [];
        }

        public function getBuilderSchema(): array {
            $schema = $this->schema();

            // prepend quickselect component
            $quickSelect = $this->quickSelect();
            if($quickSelect) {
                array_unshift($schema, $quickSelect->toFormComponent());
            }
            
            return $schema;
        }

        public function getLabel(): string {
            return static::$label;
        }

        public function getView(): string {
            return static::$view;
        }

        public function getIcon(): ?string {
            return static::$icon;
        }

        public static function getName(): string {
            return Str::snake(class_basename(static::class));
        }
    }