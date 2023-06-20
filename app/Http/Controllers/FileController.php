<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\File;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    public function index()
    {
        $files = File::where('user_id', Auth::id())->get();
        return view('file-management', ['files' => $files]);
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

    public function share(Request $request, File $file)
    {
        $recipient = User::where('username', $request->input('recipient'))->first();

        if ($recipient) {
            DB::table('shared_files')->insert([
                'user_id' => Auth::id(),
                'recipient_id' => $recipient->id,
                'file_id' => $file->id,
            ]);

            return back()->with('success', 'File shared successfully.');
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
            ->select('files.*', 'users.username as username')
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
            ->select('files.*', 'users.username as username')
            ->get();

        return view('search-results', [
            'userFiles' => $userFiles,
            'sharedFiles' => $sharedFiles,
            'query' => $query,
        ]);
    }
}
