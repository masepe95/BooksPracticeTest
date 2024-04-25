<!-- resources/views/books/index.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="row">
        @foreach ($books as $book)
            <div class="col-md-4 m-3">
                <div class="card" style="width: 18rem; height: 18rem">
                    <div class="card-body">
                        <h5 class="card-title">{{ $book->title }}</h5>
                        <p class="card-text">{{ $book->description }}</p>
                        <p class="card-text">€{{ $book->price }}</p>
                        <a href="http://localhost:8000/admin/books/{{ $book->id }}" class="btn btn-primary">About</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
