<?php

namespace Tjall\Users\Filament\Resources;

use Tjall\Users\Filament\Resources\BlockResource\Pages;
use Tjall\Pagebuilder\Models\Block;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;

class BlockResource extends Resource
{
    protected static ?string $model = Block::class;

    protected static ?string $navigationGroup = 'Pagebuilder';
    protected static ?string $navigationLabel = 'Gebruikers';
    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $label = 'gebruiker';
    protected static ?string $pluralLabel = 'gebruikers';

    public static function canDelete(mixed $record): bool
    {
        $currentUser = auth()->user();

        // the first user cannot be deleted
        if($record->is(User::first())) return false;

        // prevent the current user from deleting their own account
        if($record->is($currentUser)) return false;

        return true;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                ->before(function (Tables\Actions\DeleteBulkAction $action, \Illuminate\Support\Collection $records) {
                    $filtered = $records->filter(fn ($record) => BlockResource::canDelete($record));

                    if ($filtered->isEmpty()) {
                        Notification::make()
                            ->title('Je kunt deze gebruiker(s) niet verwijderen.')
                            ->danger()
                            ->send();
                        $action->cancel();
                    } else {
                        $action->records($filtered);
                    }
                }),
            ]);
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
