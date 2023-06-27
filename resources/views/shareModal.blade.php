<div class="modal fade" id="{{ $modalId }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareModalLabel-{{ $file->id }}">Share File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('file.share', ['file' => $file->id]) }}" method="POST"
                    id="{{ $formId }}">
                    @csrf
                    <div class="mb-3">
                        <label for="recipient-{{ $file->id }}" class="form-label">Recipient</label>
                        <select name="recipient" id="recipient-{{ $file->id }}" class="form-control" required>
                            @foreach ($users as $user)
                                @if ($user->id != Auth::user()->id)
                                    <option value="{{ $user->id }}">{{ $user->username }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" name="revoke_file_id" value="{{ $file->id }}">
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="can_view"
                                id="can_view-{{ $file->id }}" value="1">
                            <label class="form-check-label" for="can_view-{{ $file->id }}">
                                <i class="bi bi-eye"></i> Can View
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="can_edit"
                                id="can_edit-{{ $file->id }}" value="1">
                            <label class="form-check-label" for="can_edit-{{ $file->id }}">
                                <i class="bi bi-pencil"></i> Can Edit
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="can_delete"
                                id="can_delete-{{ $file->id }}" value="1">
                            <label class="form-check-label" for="can_delete-{{ $file->id }}">
                                <i class="bi bi-trash"></i> Can Delete
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-share"></i> Share
                    </button>
                </form>

                <hr>

                <h6>Shared With:</h6>
                @foreach ($file->sharedUsers as $user)
                    <div class="d-flex align-items-center justify-content-between">
                        <div>{{ $user->username }}</div>
                        <form action="{{ route('file.revoke', ['file' => $file->id, 'user' => $user->id]) }}"
                            method="POST">
                            @csrf
                            <input type="hidden" name="revoke_file_id" value="{{ $file->id }}">
                            <input type="hidden" name="revoke_user_id" value="{{ $user->id }}">
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="bi bi-x"></i> Revoke
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
