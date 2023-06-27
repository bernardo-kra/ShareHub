@extends('layouts.app-master')

@section('content')
    <div class="container py-5">
        <h1>Files shared with me</h1>
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
                            @if ($file->can_view)
                                <a href="{{ route('file.download', ['file' => $file->id]) }}" class="btn btn-primary">
                                    <i class="bi bi-download"></i> Download
                                </a>
                            @endif
                            @if ($file->can_edit)
                                <a href="{{ route('file.edit', ['file' => $file->id]) }}" class="btn btn-secondary">
                                    <i class="bi bi-pencil-fill"></i> Edit
                                </a>
                            @endif
                            @if ($file->can_delete)
                                <form action="{{ route('file.delete', ['file' => $file->id]) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-trash-fill"></i> Delete
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
