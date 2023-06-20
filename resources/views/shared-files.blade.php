@extends('layouts.app-master')

@section('content')
    <div class="container py-5">
        <h1>Files shared with me
        </h1>
        @if ($files->isEmpty())
            <div class="alert alert-info mt-4">
                <p>No shared files yet.</p>
            </div>
        @else
            <div class="mt-4">
                @foreach ($files as $file)
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">{{ $file->file_path }}</h5>
                            <p class="card-text">Shared by: {{ $file->username }}</p>
                            <a href="{{ route('file.download', ['file' => $file->id]) }}" class="btn btn-primary">Download</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
