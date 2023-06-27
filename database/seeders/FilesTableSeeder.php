<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class FilesTableSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();

        $fileNames = ['file1.txt', 'file2.txt', 'file3.txt'];

        foreach ($fileNames as $fileName) {
            $filePath = 'uploads/' . $fileName;

            Storage::disk('public')->put($filePath, 'This is a sample file content.');

            DB::table('files')->insert([
                'user_id' => $user->id,
                'file_path' => $filePath,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
