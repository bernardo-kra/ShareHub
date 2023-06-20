@extends('layouts.app-master')

@section('content')
    <div class="container py-5">
        <h1 class="mb-5">File Management</h1>

        <h2 class="mb-3">Upload a new file</h2>

        <form action="{{ route('file.upload') }}" method="POST" enctype="multipart/form-data" class="mb-5">
            @csrf
            <div class="mb-3">
                <label for="file" class="form-label">Choose File</label>
                <input type="file" name="file" id="file" class="form-control @error('file') is-invalid @enderror" required accept=".pdf,.doc,.docx,.txt">
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>

        <h2 class="mb-3">Your files</h2>

        @if ($files->isEmpty())
            <p>You have not uploaded any files yet.</p>
        @else
            @foreach ($files as $file)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">{{ $file->file_path }}</h5>
                        <form action="{{ route('file.share', ['file' => $file->id]) }}" method="POST" class="d-flex align-items-start">
                            @csrf
                            <input type="text" name="recipient" placeholder="Username to share with" required class="form-control mr-2">
                            <button type="submit" class="btn btn-primary mr-2">Share</button>
                            <a href="{{ route('file.download', ['file' => $file->id]) }}" class="btn btn-secondary">Download</a>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection
