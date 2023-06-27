<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\File;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class FileController extends Controller
{
    public function index()
    {
        $files = File::where('user_id', Auth::id())->get();
        $users = User::all();
        return view('file-management', ['files' => $files, 'users' => $users]);
    }

    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:pdf,doc,docx,txt|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $name = time() . $file->getClientOriginalName();
            $filePath = 'uploads/' . $name;
            Storage::disk('public')->put($filePath, file_get_contents($file));

            $file = new File;
            $file->user_id = Auth::id();
            $file->file_path = $filePath;
            $file->save();

            return back()->with('success', 'File uploaded successfully.');
        }
    }

    public function createTextFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_name' => 'required|string|max:255',
            'file_content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $fileName = time() . '_' . $request->file_name . '.txt';
        $filePath = 'uploads/' . $fileName;
        Storage::disk('public')->put($filePath, $request->file_content);

        $file = new File;
        $file->user_id = Auth::id();
        $file->file_path = $filePath;
        $file->save();

        return back()->with('success', 'Text file created successfully.');
    }

    public function share(Request $request, File $file)
    {
        $recipientId = $request->input('recipient');
        $recipient = User::find($recipientId);

        if ($recipient instanceof User) {
            $sharedFile = $file->sharedUsers()->where('recipient_id', $recipient->id)->first();

            if (!$sharedFile) {
                $file->sharedUsers()->attach($recipient, [
                    'recipient_id' => $recipient->id,
                    'can_view' => $request->has('can_view') ? 1 : 0,
                    'can_edit' => $request->has('can_edit') ? 1 : 0,
                    'can_delete' => $request->has('can_delete') ? 1 : 0,
                ]);

                return back()->with('success', 'File shared successfully.');
            }
        }

        return back()->with('error', 'Recipient not found or file already shared with the recipient.');
    }

    public function revokeShare(Request $request, File $file)
    {
        $recipientId = $request->input('revoke_user_id');
        $recipient = User::find($recipientId);

        if ($recipient) {
            $sharedFile = DB::table('shared_files')
                ->where('file_id', $file->id)
                ->where('user_id', $recipient->id)
                ->first();

            if ($sharedFile) {
                DB::table('shared_files')->where('id', $sharedFile->id)->delete();

                return back()->with('success', 'File sharing revoked successfully.');
            } else {
                return back()->with('error', 'This file was not shared with the specified user.');
            }
        } else {
            return back()->with('error', 'Recipient not found.');
        }
    }

    public function sharedWithMe()
    {
        $sharedFiles = DB::table('shared_files')
            ->where('shared_files.recipient_id', Auth::id())
            ->join('files', 'files.id', '=', 'shared_files.file_id')
            ->join('users', 'users.id', '=', 'shared_files.user_id')
            ->select('files.*', 'users.username as username', 'shared_files.can_view', 'shared_files.can_edit', 'shared_files.can_delete')
            ->get();

        return view('shared-files', ['files' => $sharedFiles]);
    }


    public function download(File $file)
    {
        $user_id = Auth::id();

        if ($user_id === $file->user_id) {
            return Storage::disk('public')->download($file->file_path);
        }

        $sharedFile = DB::table('shared_files')
            ->where('file_id', $file->id)
            ->where('recipient_id', $user_id)
            ->first();

        if ($sharedFile) {
            return Storage::disk('public')->download($file->file_path);
        }

        return redirect()->back()->with('error', 'You do not have permission to download this file.');
    }

    public function search(Request $request)
{
    $query = $request->input('search');

    $userFiles = File::where('user_id', Auth::id())
        ->where('file_path', 'LIKE', "%{$query}%")
        ->get();

    $sharedFiles = DB::table('shared_files')
        ->where('shared_files.recipient_id', Auth::id())
        ->join('files', function ($join) use ($query) {
            $join->on('files.id', '=', 'shared_files.file_id')
                ->where('files.file_path', 'LIKE', "%{$query}%");
        })
        ->join('users', 'users.id', '=', 'shared_files.user_id')
        ->select('files.*', 'users.username as shared_by')
        ->get();

    return view('search-results', [
        'userFiles' => $userFiles,
        'sharedFiles' => $sharedFiles,
        'query' => $query,
    ]);
}

    public function edit(File $file)
    {
        $user_id = Auth::id();

        if ($user_id === $file->user_id || $this->checkPermission($file, 'can_edit')) {
            return view('file.edit', ['file' => $file]);
        }

        return back()->with('error', 'You do not have permission to edit this file.');
    }

    public function update(Request $request, File $file)
    {
        $user_id = Auth::id();

        if ($user_id === $file->user_id || $this->checkPermission($file, 'can_edit')) {
            $file->update($request->all());
            return back()->with('success', 'File updated successfully.');
        }

        return back()->with('error', 'You do not have permission to edit this file.');
    }

    public function destroy(File $file)
{
    $user_id = Auth::id();

    if ($user_id === $file->user_id || $this->checkPermission($file, 'can_delete')) {

        DB::table('shared_files')->where('file_id', $file->id)->delete();

        $file->delete();
        return back()->with('success', 'File deleted successfully.');
    }

    return back()->with('error', 'You do not have permission to delete this file.');
}


    private function checkPermission(File $file, string $permission): bool
    {
        $sharedFile = DB::table('shared_files')
            ->where('file_id', $file->id)
            ->where('recipient_id', Auth::id())
            ->first();

        return $sharedFile && $sharedFile->{$permission};
    }

}