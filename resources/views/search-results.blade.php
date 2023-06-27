@extends('layouts.app-master')

@section('content')
    <div class="container">
        <h1>Search results for: "{{ $query }}"</h1>

        <h2>My files</h2>
        @if ($userFiles->isEmpty())
            <p>No files found.</p>
        @else
            <ul class="list-group">
                @foreach ($userFiles as $file)
                    <li class="list-group-item">
                        <span class="file-path">{{ $file->file_path }}</span>
                        <a href="{{ route('file.download', ['file' => $file->id]) }}" class="btn btn-primary">Download</a>
                    </li>
                @endforeach
            </ul>
        @endif

        <h2>Shared Files</h2>
        @if ($sharedFiles->isEmpty())
            <p>No shared files found.</p>
        @else
            <ul class="list-group">
                @foreach ($sharedFiles as $file)
                    <li class="list-group-item">
                        <span class="file-path">{{ $file->file_path }}</span>
                        <p class="shared-by">Shared by: {{ $file->username }}</p>
                        <a href="{{ route('file.download', ['file' => $file->id]) }}" class="btn btn-primary">Download</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
