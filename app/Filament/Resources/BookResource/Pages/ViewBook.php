<?php

namespace App\Filament\Resources\BookResource\Pages;

use App\Filament\Resources\BookResource;
use App\Models\Book;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewBook extends ViewRecord
{
    protected static string $resource = BookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('attach')
                ->label('Aggiungi ai Preferiti')
                ->visible(fn (Book $record) => !$record->users()->where('user_id', auth()->id())->exists())
                ->action(function (Book $record) {
                    $userId = auth()->id(); // Ottieni l'ID dell'utente loggato
                    $record->users()->attach($userId); // Esegui l'attach nella tabella pivot
                    return redirect()->back()->with('success', 'Libro aggiunto ai preferiti!');
                }),
            Action::make('detach')
                ->label('Rimuovi dai Preferiti')
                ->visible(fn (Book $record) => $record->users()->where('user_id', auth()->id())->exists())
                ->action(function (Book $record) {
                    $userId = auth()->id(); // Ottieni l'ID dell'utente loggato
                    $record->users()->detach($userId); // Esegui l'attach nella tabella pivot
                    return redirect()->back()->with('success', 'Libro aggiunto ai preferiti!');
                }),
        ];
    }
}
