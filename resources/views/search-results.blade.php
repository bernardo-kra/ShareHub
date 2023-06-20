@extends('layouts.app-master')

@section('content')
    <div class="container">
        <h1>Search results for: "{{ $query }}"</h1>

        <h2>My files</h2>
        @if ($userFiles->isEmpty())
            <p>No files found.</p>
        @else
            <ul>
                @foreach ($userFiles as $file)
                    <li>
                        {{ $file->file_path }}
                        <a href="{{ route('file.download', ['file' => $file->id]) }}">Download</a>
                    </li>
                @endforeach
            </ul>
        @endif

        <h2>Shared Files</h2>
        @if ($sharedFiles->isEmpty())
            <p>No shared files found.</p>
        @else
            <ul>
                @foreach ($sharedFiles as $file)
                    <li>
                        {{ $file->file_path }}
                        <p>Shared by: {{ $file->username }}</p>
                        <a href="{{ route('file.download', ['file' => $file->id]) }}">Download</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
