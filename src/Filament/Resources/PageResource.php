<?php

namespace Tjall\Pagebuilder\Filament\Resources;

use Tjall\Pagebuilder\Filament\Resources\PageResource\Pages;
use Tjall\Pagebuilder\Models\Page;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms;
use Tjall\Pagebuilder\Enums\PageTypeEnum;
use Filament\Tables\Columns;
use Tjall\Pagebuilder\Filament\Forms\Components\Pagebuilder;
use Tjall\Pagebuilder\Filament\Forms\Components\Pageheader;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;

class PageResource extends Resource
{
    protected static ?string $slug = 'pagebuilder-pages';

    protected static ?string $model = Page::class;

    protected static ?string $navigationGroup = 'Pagina\'s';
    protected static ?string $navigationIcon = 'heroicon-o-document';

    protected static ?string $label = 'pagina';
    protected static ?string $pluralLabel = 'pagina\'s';

    public static function form(Form $form): Form
    {       
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Titel')
                    ->placeholder('Nieuwe pagina')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('path')
                    ->helperText('Gebruik {slug} of {id} voor een template.')
                    ->placeholder('/nieuwe-pagina')
                    ->dehydrateStateUsing(fn ($state) => '/'.trim(trim($state), '/'))
                    ->label('Pad')
                    ->required(),
                Tabs::make()
                    ->columnSpanFull()
                    ->tabs([
                        Tabs\Tab::make('pagebuilder')
                            ->label('Inhoud')
                            ->schema([
                                Pagebuilder::make('pagebuilder'),
                            ]),
                        // Tabs\Tab::make('pageheader')
                        //     ->label('Header')
                        //     ->schema([
                        //         Pageheader::make('header')
                        //     ]),
                        Tabs\Tab::make('settings')
                            ->label('Instellingen')
                            ->columns(2)
                            ->schema([
                                Forms\Components\Select::make('type')
                                    ->label('Paginatype')
                                    ->options(PageTypeEnum::class)
                                    ->default(PageTypeEnum::Static)
                                    ->live()
                                    ->required()
                                    ->selectablePlaceholder(false),
                                Forms\Components\Select::make('model')
                                    ->label('Model')
                                    ->options(self::getModelOptions())
                                    ->required()
                                    ->selectablePlaceholder(false)
                            ]),
                    ]),
            ]);
    }

    public static function afterSave(Model $record): void
    {
        $record->updateRoute(true);
    }

    protected static function isTemplate(Forms\Get $get): bool {
        return $get('type') === PageTypeEnum::Template;
    }

    protected static function getModelOptions(): array
    {
        $models = config('pagebuilder.models', []);

        $options = [];
        foreach ($models as $model) {
            $options[$model] = class_basename($model);
        }

        return $options;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Columns\TextColumn::make('title')
                    ->label('Titel')
                    ->sortable()
                    ->searchable(),
                Columns\TextColumn::make('path')
                    ->label('Pad')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('title', 'asc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
