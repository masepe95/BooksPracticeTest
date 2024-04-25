<?php

namespace App\Filament\Resources\BookResource\Pages;

use App\Filament\Resources\BookResource;
use App\Models\Book;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListBooks extends ListRecords
{
    protected static string $resource = BookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getTabs(): array
    {
        $userId = Auth::id();
        $user = User::with('books')->find($userId);

        return [
            'All Books' => Tab::make('All Books'),
            'My Favorite Books' => Tab::make()->query(function () use ($user) {
                return $user->books()->getQuery(); // Usiamo getQuery() per ottenere un'istanza di Illuminate\Database\Eloquent\Builder
            }),
        ];
    }
}
