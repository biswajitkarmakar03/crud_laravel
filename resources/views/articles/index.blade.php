@extends('articles.layout')

@section('content')

<h1>Articles</h1>

<a href="{{ route('articles.create') }}" class="btn btn-primary mb-3">
    Create Article
</a>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($articles as $article)
                    <tr>
                        <td>{{ $article->title }}</td>
                        <td>{{ $article->content }}</td>
                        <td>
                            <a href="{{ route('articles.edit', $article->id) }}"
                               class="btn btn-warning">
                                Edit
                            </a>

                            <form action="{{ route('articles.destroy', $article->id) }}"
                                  method="POST">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection