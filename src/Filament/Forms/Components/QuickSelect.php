<?php
    namespace Tjall\Pagebuilder\Filament\Forms\Components;

    use Filament\Forms\Components\Grid;
    use Filament\Forms\Components\Select;
    use Filament\Forms\Components\Concerns;
    use Illuminate\Support\Str;
    use Illuminate\Contracts\Support\Arrayable;
    use Closure;
    use Filament\Forms\Components\Radio;
    use Filament\Forms\Components\TextInput;
    use Filament\Forms\Get;
    use Illuminate\Database\Eloquent\Collection;
    use Filament\Forms\Components\Hidden;

    class QuickSelect extends Grid {
        protected ?string $dateAttribute = null;

        protected ?string $recordLabel = null;

        protected ?string $model_ = null;

        public function setUp(): void {
            parent::setUp();

            $this
                ->columns(2)
                ->schema([
                    Select::make('view')
                        ->label('Weergave')
                        ->options(function() {
                            $options = [
                                'all'      => 'Alle ' . $this->label,
                                'recent'   => 'Recente ' . $this->label,
                                'selected' => 'Geselecteerde ' . $this->label,
                            ];

                            if(!$this->dateAttribute) {
                                unset($options['recent']);
                            }

                            return $options;
                        })
                        ->default('selected')
                        ->live()
                        ->required(),
                    Select::make('records')
                        ->label(fn() => 'Kies ' . $this->label)
                        ->visible(fn (Get $get) => $get('view') === 'selected')
                        ->options(fn () => $this->model_::query()
                            ->orderBy('created_at', 'desc')
                            ->pluck($this->recordLabel, 'id'))
                        ->multiple()
                        ->searchable()
                        ->required(),
                    TextInput::make('limit')
                        ->label('Aantal')
                        ->visible(fn (Get $get) => $get('view') === 'recent')
                        ->numeric()
                        ->minValue(1)
                        ->default(3)
                        ->required()
                ]);
        }

        public function dateAttribute(string $dateAttribute = 'created_at'): static {
            $this->dateAttribute = is_string($dateAttribute) ? $dateAttribute : null;
            return $this;
        }

        public function recordLabel(string $recordLabel): static {
            $this->recordLabel = $recordLabel;
            return $this;
        }

        public function model_(string $model): static {
            $this->model_ = $model;
            return $this;
        }
    }