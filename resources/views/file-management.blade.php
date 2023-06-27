@extends('layouts.app-master')

@section('content')
    <div class="container py-5">
        <h1 class="mb-5">File Management</h1>

        <h2 class="mb-3">Create a new text file</h2>

        <form action="{{ route('file.createTextFile') }}" method="POST" class="mb-5">
            @csrf
            <div class="mb-3">
                <label for="file_name" class="form-label">File Name</label>
                <input type="text" name="file_name" id="file_name"
                    class="form-control @error('file_name') is-invalid @enderror" required>
            </div>
            <div class="mb-3">
                <label for="file_content" class="form-label">File Content</label>
                <textarea name="file_content" id="file_content" class="form-control @error('file_content') is-invalid @enderror"
                    required></textarea>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-file-plus"></i> Create</button>
        </form>

        <h2 class="mb-3">Upload a new file</h2>

        <form action="{{ route('file.upload') }}" method="POST" enctype="multipart/form-data" class="mb-5">
            @csrf
            <div class="mb-3">
                <label for="file" class="form-label">Choose File</label>
                <input type="file" name="file" id="file" class="form-control @error('file') is-invalid @enderror"
                    required accept=".pdf,.doc,.docx,.txt">
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-cloud-upload"></i> Upload</button>
        </form>

        <h2 class="mb-3">Your files</h2>

        @if ($files->isEmpty())
            <p>You have not uploaded any files yet.</p>
        @else
            @foreach ($files as $file)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">{{ $file->file_path }}</h5>
                        <form action="{{ route('file.share', ['file' => $file->id]) }}" method="POST"
                            class="d-flex align-items-start">
                            @csrf
                            <button type="button" class="btn btn-primary mr-2" data-bs-toggle="modal"
                                data-bs-target="#shareModal-{{ $file->id }}"><i class="bi bi-share"></i> Share</button>
                            <a href="{{ route('file.download', ['file' => $file->id]) }}"
                                class="btn btn-secondary ms-2"><i class="bi bi-download"></i> Download</a>
                        </form>
                    </div>
                </div>
                @include('shareModal', ['file' => $file, 'formId' => 'shareForm-' . $file->id, 'modalId' => 'shareModal-' . $file->id])
            @endforeach
        @endif
    </div>
@endsection
