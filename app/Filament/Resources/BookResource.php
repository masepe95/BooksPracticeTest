<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookResource\Pages;
use App\Filament\Resources\BookResource\RelationManagers;
use App\Models\Book;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Title'),
                Forms\Components\TextInput::make('description')
                    ->label('Description'),
                Forms\Components\TextInput::make('price')
                    ->label('Price')
                    ->numeric()
                    ->prefix('€')
                    ->maxValue(42949672.95),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Title'),
                TextColumn::make('description')->label('Description'),
                TextColumn::make('price')->label('Price'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(Auth::user()->isAdmin == true),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('attach')
                    ->label('Aggiungi ai Preferiti')
                    ->visible(fn (Book $record) => !$record->users()->where('user_id', auth()->id())->exists())
                    ->action(function (Book $record) {
                        $userId = auth()->id(); // Ottieni l'ID dell'utente loggato
                        $record->users()->attach($userId); // Esegui l'attach nella tabella pivot
                        return redirect()->back()->with('success', 'Libro aggiunto ai preferiti!');
                    }),
                Tables\Actions\Action::make('detach')
                    ->label('Rimuovi dai Preferiti')
                    ->visible(fn (Book $record) => $record->users()->where('user_id', auth()->id())->exists())
                    ->action(function (Book $record) {
                        $userId = auth()->id(); // Ottieni l'ID dell'utente loggato
                        $record->users()->detach($userId); // Esegui l'attach nella tabella pivot
                        return redirect()->back()->with('success', 'Libro aggiunto ai preferiti!');
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\UsersRelationManager::class,

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'view' => Pages\ViewBook::route('/{record}'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return Auth::user()->isAdmin == true;
    }
}
