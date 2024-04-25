<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::all(); // Recupera tutti i libri disponibili
        return view('books.index', compact('books'));
    }
    public function search(Request $request)
    {
        $search = $request->input('search');
        $books = Book::where('title', 'like', "%$search%")
            ->orWhere('description', 'like', "%$search%")
            ->get();

        return view('books.index', compact('books'));
    }
}
